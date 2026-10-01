<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminInscriptionController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->query('statut', Inscription::EN_ATTENTE);
        $q = trim((string) $request->query('q'));

        $inscriptions = Inscription::query()
            ->when($statut !== 'toutes', fn ($r) => $r->where('statut', $statut))
            ->when($q !== '', function ($r) use ($q) {
                $like = '%'.$q.'%';
                $r->where(fn ($w) => $w->where('nom', 'like', $like)
                    ->orWhere('prenom', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('telephone', 'like', $like)
                    ->orWhere('reference_transaction', 'like', $like));
            })
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.index', [
            'inscriptions' => $inscriptions,
            'statut' => $statut,
            'q' => $q,
            'compte' => Inscription::selectRaw('statut, count(*) as n')->groupBy('statut')->pluck('n', 'statut'),
            'places' => config('formation.places'),
        ]);
    }

    public function valider(Inscription $inscription)
    {
        if ($inscription->statut !== Inscription::PAYEE) {
            $inscription->update([
                'statut' => Inscription::PAYEE,
                'valide_le' => now(),
                'motif_refus' => null,
            ]);

            $this->envoyer($inscription,
                'Inscription confirmée - '.config('formation.titre'),
                "Bonjour {$inscription->prenom},\n\n"
                ."Votre paiement a été vérifié : votre inscription à « ".config('formation.titre')." » est confirmée.\n\n"
                .'Formation(s) : '.$inscription->libelleFormations()."\n"
                .'Dates : '.config('formation.dates')."\n"
                .'Lieu : '.config('formation.lieu')."\n"
                ."Montant réglé : {$inscription->montant} {$inscription->devise} ({$inscription->libelleOperateur()})\n"
                ."Référence de transaction : {$inscription->reference_transaction}\n\n"
                .(config('formation.contact') ? 'Une question ? '.config('formation.contact')."\n\n" : '')
                .'À bientôt !');
        }

        return back()->with('ok', "Inscription de {$inscription->prenom} {$inscription->nom} validée.");
    }

    public function refuser(Request $request, Inscription $inscription)
    {
        $data = $request->validate(['motif' => ['nullable', 'string', 'max:200']]);
        $motif = $data['motif'] ?: 'Nous n\'avons pas retrouvé votre paiement.';

        if ($inscription->statut !== Inscription::PAYEE) {
            $inscription->update(['statut' => Inscription::REFUSEE, 'motif_refus' => $motif]);

            $this->envoyer($inscription,
                'Votre inscription n\'a pas pu être validée - '.config('formation.titre'),
                "Bonjour {$inscription->prenom},\n\n"
                ."Nous n'avons pas pu valider votre inscription : {$motif}\n\n"
                ."Vous pouvez corriger vos informations et renvoyer votre demande ici : ".route('inscription.create')."\n"
                .(config('formation.contact') ? 'Ou nous contacter : '.config('formation.contact')."\n" : ''));
        }

        return back()->with('ok', "Inscription de {$inscription->prenom} {$inscription->nom} refusée.");
    }

    /** Export CSV (ouvrable dans Excel) des inscriptions validées. */
    public function export()
    {
        $lignes = Inscription::where('statut', Inscription::PAYEE)->orderBy('nom')->get();

        return response()->streamDownload(function () use ($lignes) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($f, ['Nom', 'Prénom', 'Email', 'Téléphone', 'Ville', 'Profession', 'Formations', 'Opérateur', 'Identifiant transaction', 'Montant', 'Validée le'], ';');
            foreach ($lignes as $i) {
                fputcsv($f, [$i->nom, $i->prenom, $i->email, $i->telephone, $i->ville, $i->profession,
                    $i->libelleFormations(), $i->libelleOperateur(), $i->reference_transaction, $i->montant, $i->valide_le?->format('Y-m-d H:i')], ';');
            }
            fclose($f);
        }, 'inscrits-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function envoyer(Inscription $i, string $sujet, string $texte): void
    {
        try {
            Mail::raw($texte, fn ($m) => $m->to($i->email)->subject($sujet));
        } catch (\Throwable $e) {
            report($e); // l'action reste enregistrée même si l'email échoue
        }
    }
}