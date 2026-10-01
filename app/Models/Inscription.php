<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    public const EN_ATTENTE = 'en_attente_verification';
    public const PAYEE = 'payee';
    public const REFUSEE = 'refusee';

    protected $fillable = [
        'reference', 'prenom', 'nom', 'email', 'telephone', 'ville', 'profession',
        'operateur', 'formations', 'reference_transaction', 'montant', 'devise',
        'statut', 'motif_refus', 'valide_le',
    ];

    protected $casts = [
        'valide_le' => 'datetime',
        'formations' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    public function libelleOperateur(): string
    {
        return $this->operateur === 'orange' ? 'Orange Money' : 'Moov Money';
    }

    /** Total à payer pour une liste de packs (clés de config/formation.php). */
    public static function total(array $cles): int
    {
        return (int) collect($cles)->sum(fn ($c) => (int) config("formation.packs.$c.prix", 0));
    }

    /** Libellés des packs choisis, séparés par des virgules. */
    public function libelleFormations(): string
    {
        return collect($this->formations ?? [])
            ->map(fn ($c) => config("formation.packs.$c.libelle", $c))
            ->join(', ');
    }

    /** Message WhatsApp prérempli (modèle dans config/formation.php). */
    public function messageWhatsapp(): string
    {
        return strtr((string) config('formation.message_whatsapp'), [
            ':prenom'  => $this->prenom,
            ':titre'   => config('formation.titre'),
            ':dates'   => config('formation.dates'),
            ':lieu'    => config('formation.lieu'),
            ':contact' => config('formation.contact'),
        ]);
    }

    /**
     * Lien "click-to-chat" WhatsApp : ouvre WhatsApp avec le message prêt à envoyer.
     * Aucun service externe : c'est vous qui appuyez sur "Envoyer".
     */
    public function lienWhatsapp(): string
    {
        $numero = ltrim(preg_replace('/\D/', '', $this->telephone), '0'); // format international sans "+"

        return 'https://wa.me/'.$numero.'?text='.rawurlencode($this->messageWhatsapp());
    }
}