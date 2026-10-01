<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInscriptionRequest;
use App\Models\Inscription;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

class InscriptionController extends Controller
{
    public function create()
    {
        return view('inscription.create', ['complet' => $this->complet()]);
    }

    public function store(StoreInscriptionRequest $request)
    {
        if ($this->complet()) {
            return back()->withInput()->withErrors(['email' => 'Les inscriptions sont closes : toutes les places sont prises.']);
        }

        $d = $request->safe()->except('website');
        $d['formations'] = array_values(array_unique($d['formations']));

        if (Inscription::where('email', $d['email'])->where('statut', Inscription::PAYEE)->exists()) {
            return back()->withInput()->withErrors(['email' => 'Cette adresse email est déjà inscrite et validée.']);
        }

        // Une personne dont l'inscription est en attente ou refusée corrige sa demande au lieu d'en créer une autre
        $existante = Inscription::where('email', $d['email'])
            ->whereIn('statut', [Inscription::EN_ATTENTE, Inscription::REFUSEE])
            ->first();

        $dejaUtilisee = Inscription::where('reference_transaction', $d['reference_transaction'])
            ->when($existante, fn ($q) => $q->whereKeyNot($existante->getKey()))
            ->exists();

        if ($dejaUtilisee) {
            return back()->withInput()->withErrors([
                'reference_transaction' => 'Cet identifiant de transaction a déjà été utilisé. Vérifiez le SMS de confirmation de votre paiement.',
            ]);
        }

        $inscription = $existante ?? new Inscription(['reference' => (string) Str::uuid()]);

        try {
            $inscription->fill($d + [
                // Le montant attendu vient TOUJOURS du serveur, calculé depuis les packs choisis
                'montant' => Inscription::total($d['formations']),
                'devise' => config('formation.devise'),
                'statut' => Inscription::EN_ATTENTE,
                'motif_refus' => null,
            ])->save();
        } catch (UniqueConstraintViolationException) {
            return back()->withInput()->withErrors([
                'reference_transaction' => 'Cet identifiant de transaction a déjà été utilisé.',
            ]);
        }

        return redirect()->route('inscription.statut', $inscription->reference);
    }

    public function statut(Inscription $inscription)
    {
        return view('inscription.statut', ['inscription' => $inscription]);
    }

    private function complet(): bool
    {
        $max = config('formation.places');

        return $max && Inscription::where('statut', Inscription::PAYEE)->count() >= $max;
    }
}