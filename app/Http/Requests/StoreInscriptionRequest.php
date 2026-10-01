<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $tel = preg_replace('/[^\d+]/', '', (string) $this->input('telephone'));
        $chiffres = ltrim($tel, '+');

        if (! str_starts_with($tel, '+')) {
            // 8 chiffres = numéro burkinabè sans indicatif
            $tel = strlen($chiffres) === 8 ? '+226'.$chiffres : '+'.$chiffres;
        }

        $this->merge([
            'telephone' => $tel,
            'email' => strtolower(trim((string) $this->input('email'))),
            // Identifiant de transaction : sans espaces, en majuscules
            'reference_transaction' => strtoupper(preg_replace('/\s+/', '', (string) $this->input('reference_transaction'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'prenom'     => ['required', 'string', 'max:80'],
            'nom'        => ['required', 'string', 'max:80'],
            'email'      => ['required', 'email:rfc', 'max:150'],
            'telephone'  => ['required', 'regex:/^\+\d{8,15}$/'],
            'ville'      => ['nullable', 'string', 'max:80'],
            'profession' => ['nullable', 'string', 'max:120'],
            'formations'   => ['required', 'array', 'min:1'],
            'formations.*' => ['string', Rule::in(array_keys(config('formation.packs')))],
            'operateur'  => ['required', 'in:orange,moov'],
            'reference_transaction' => ['required', 'regex:/^[A-Z0-9._\-]{6,40}$/'],
            'website'    => ['nullable', 'max:0'], // champ piège anti-robots
        ];
    }

    public function messages(): array
    {
        return [
            'prenom.required'    => 'Indiquez votre prénom.',
            'nom.required'       => 'Indiquez votre nom.',
            'email.required'     => 'Indiquez votre adresse email : la confirmation y sera envoyée.',
            'email.email'        => 'Cette adresse email semble incorrecte.',
            'telephone.required' => 'Indiquez le numéro avec lequel vous avez payé.',
            'telephone.regex'    => 'Numéro invalide. Exemple : 70 12 34 56 ou +226 70 12 34 56.',
            'formations.required' => 'Choisissez au moins une formation.',
            'formations.min'      => 'Choisissez au moins une formation.',
            'formations.*.in'     => 'Formation invalide.',
            'operateur.required' => 'Choisissez l\'opérateur avec lequel vous avez payé.',
            'operateur.in'       => 'Choisissez Orange Money ou Moov Money.',
            'reference_transaction.required' => 'Indiquez l\'identifiant de transaction reçu par SMS après votre paiement.',
            'reference_transaction.regex'    => 'Identifiant invalide : 6 à 40 caractères (lettres, chiffres, points, tirets). Recopiez-le tel qu\'il apparaît dans le SMS.',
            'website.max'        => 'Requête refusée.',
        ];
    }
}