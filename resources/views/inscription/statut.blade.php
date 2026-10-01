<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Votre inscription – {{ config('formation.titre') }}</title>
  @include('inscription._styles')
</head>
<body>
<main class="statut">
  <div class="statut-carte">
  @switch($inscription->statut)
    @case('payee')
      <div class="pastille ok" aria-hidden="true">✓</div>
      <h1>Inscription confirmée</h1>
      <p>Merci {{ $inscription->prenom }}, votre paiement a été vérifié. Une confirmation est envoyée à {{ $inscription->email }}.</p>
      <dl>
        <dt>Formation</dt><dd>{{ $inscription->libelleFormations() ?: config('formation.titre') }}</dd>
        <dt>Dates</dt><dd>{{ config('formation.dates') }}</dd>
        <dt>Lieu</dt><dd>{{ config('formation.lieu') }}</dd>
      </dl>
      @break

    @case('refusee')
      <div class="pastille refus" aria-hidden="true">✕</div>
      <h1>Inscription non validée</h1>
      <p>{{ $inscription->motif_refus ?: 'Nous n\'avons pas retrouvé votre paiement.' }}</p>
      <p>Vous pouvez corriger vos informations (par exemple l'identifiant de transaction) et renvoyer votre demande.
        @if (config('formation.contact')) Ou nous contacter : {{ config('formation.contact') }}. @endif</p>
      <a class="bouton" href="{{ route('inscription.create') }}">Corriger mon inscription</a>
      @break

    @default
      <div class="pastille attente" aria-hidden="true">⏳</div>
      <h1>Inscription reçue</h1>
      <p>Merci {{ $inscription->prenom }}. Nous vérifions votre paiement {{ config('formation.delai_verification') }}.
        Vous recevrez un email à {{ $inscription->email }} dès que votre inscription sera confirmée.</p>
      <dl>
        <dt>Formation(s)</dt><dd>{{ $inscription->libelleFormations() }}</dd>
        <dt>Opérateur</dt><dd>{{ $inscription->libelleOperateur() }}</dd>
        <dt>Identifiant</dt><dd>{{ $inscription->reference_transaction }}</dd>
        <dt>Montant attendu</dt><dd>{{ number_format($inscription->montant, 0, ',', "\u{00A0}") }}&nbsp;FCFA</dd>
      </dl>
      <p class="note">Vous pouvez fermer cette page. Gardez votre SMS de paiement jusqu'à la confirmation.
        @if (config('formation.contact')) Une question ? {{ config('formation.contact') }} @endif</p>
  @endswitch
  </div>
</main>
</body>
</html>