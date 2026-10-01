@php
  $packs = config('formation.packs');
  $orange = config('formation.paiement.orange');
  $moov = config('formation.paiement.moov');
  $titulaire = config('formation.paiement.titulaire');
  $choisis = old('formations', []);
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscription – {{ config('formation.titre') }}</title>
  @include('inscription._styles')
</head>
<body>
<main class="page">
  <section class="resume">
    <span class="etiquette">{{ $complet ? 'Inscriptions closes' : 'Inscriptions ouvertes' }}</span>
    <h1>{{ config('formation.titre') }}</h1>
    <p class="intro">{{ config('formation.description') }}</p>

    <ul class="infos">
      <li><span class="ico" aria-hidden="true">📅</span><span><small>Dates</small>{{ config('formation.dates') }}</span></li>
      <li><span class="ico" aria-hidden="true">📍</span><span><small>Lieu</small>{{ config('formation.lieu') }}</span></li>
      @if (config('formation.places'))
        <li><span class="ico" aria-hidden="true">👥</span><span><small>Places</small>{{ config('formation.places') }} places</span></li>
      @endif
    </ul>

    <h2 class="sous-titre">Nos formules</h2>
    <ul class="tarifs">
      @foreach ($packs as $p)
        <li class="pack-{{ $loop->iteration }}">
          <span class="ico" aria-hidden="true">{{ $p['icone'] ?? '🎓' }}</span>
          <span class="nom">{{ $p['libelle'] }}</span>
          <span class="prix">{{ number_format($p['prix'], 0, ',', "\u{00A0}") }}&nbsp;FCFA</span>
        </li>
      @endforeach
    </ul>

    @if (config('formation.contact'))
      <p class="hero-contact">Une question ? {{ config('formation.contact') }}</p>
    @endif
  </section>

  <section class="carte" aria-labelledby="titre-formations">
    @if ($complet)
      <div class="alerte" role="alert">Les inscriptions sont closes : toutes les places sont prises.
        @if (config('formation.contact')) Contact : {{ config('formation.contact') }} @endif</div>
    @else
      @if ($errors->any() && ! $errors->hasAny(['prenom','nom','email','telephone','operateur','reference_transaction','formations']))
        <div class="alerte" role="alert">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('inscription.store') }}" id="form-inscription" novalidate>
        @csrf
        <div class="piege" aria-hidden="true">
          <label>Ne pas remplir <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>

        <h2 id="titre-formations">Choisissez vos formations</h2>
        <fieldset class="champ" @error('formations') aria-describedby="e-form" @enderror>
          <legend class="opt">Vous pouvez cocher un pack ou les deux</legend>
          <div class="packs">
            @foreach ($packs as $cle => $p)
              <label class="pack-{{ $loop->iteration }}">
                <input type="checkbox" name="formations[]" value="{{ $cle }}" data-prix="{{ $p['prix'] }}"
                       @checked(in_array($cle, $choisis, true))>
                <span class="ico" aria-hidden="true">{{ $p['icone'] ?? '🎓' }}</span>
                <span class="texte">{{ $p['libelle'] }}<small>{{ $p['inclus'] }}</small></span>
                <span class="prix-pack">{{ number_format($p['prix'], 0, ',', "\u{00A0}") }}&nbsp;FCFA</span>
              </label>
            @endforeach
          </div>
          @error('formations')<p class="erreur" id="e-form">{{ $message }}</p>@enderror
        </fieldset>

        <h2>Payez les frais de formation</h2>
        <div class="paiement">
          <div class="total"><span>Montant à envoyer</span><span class="num" id="total">selon vos choix</span></div>
          @if ($orange)<div class="orange"><span>Orange Money</span><span class="num">{{ $orange }}</span></div>@endif
          @if ($moov)<div class="moov"><span>Moov Money</span><span class="num">{{ $moov }}</span></div>@endif
          @if ($titulaire)<div><span>Nom du bénéficiaire</span><span>{{ $titulaire }}</span></div>@endif
        </div>
        <ol class="etapes">
          <li>Envoyez le montant exact au numéro de votre opérateur.</li>
          <li>Vous recevez un SMS de confirmation contenant un identifiant de transaction : gardez-le.</li>
          <li>Recopiez cet identifiant dans le formulaire ci-dessous.</li>
        </ol>

        <h2>Renseignez vos informations</h2>

        <div class="deux">
          <div class="champ">
            <label for="prenom">Prénom</label>
            <input id="prenom" name="prenom" value="{{ old('prenom') }}" autocomplete="given-name" required
                   @error('prenom') aria-invalid="true" aria-describedby="e-prenom" @enderror>
            @error('prenom')<p class="erreur" id="e-prenom">{{ $message }}</p>@enderror
          </div>
          <div class="champ">
            <label for="nom">Nom</label>
            <input id="nom" name="nom" value="{{ old('nom') }}" autocomplete="family-name" required
                   @error('nom') aria-invalid="true" aria-describedby="e-nom" @enderror>
            @error('nom')<p class="erreur" id="e-nom">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="champ">
          <label for="email">Adresse email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
                 @error('email') aria-invalid="true" aria-describedby="e-email" @enderror>
          @error('email')<p class="erreur" id="e-email">{{ $message }}</p>@enderror
        </div>

        <div class="deux">
          <div class="champ">
            <label for="ville">Ville <span class="opt">(facultatif)</span></label>
            <input id="ville" name="ville" value="{{ old('ville') }}" autocomplete="address-level2">
          </div>
          <div class="champ">
            <label for="profession">Profession <span class="opt">(facultatif)</span></label>
            <input id="profession" name="profession" value="{{ old('profession') }}">
          </div>
        </div>

        <fieldset class="champ" @error('operateur') aria-describedby="e-op" @enderror>
          <legend>Opérateur avec lequel vous avez payé</legend>
          <div class="choix">
            <label><input type="radio" name="operateur" value="orange" required @checked(old('operateur') === 'orange')> Orange Money</label>
            <label><input type="radio" name="operateur" value="moov" required @checked(old('operateur') === 'moov')> Moov Money</label>
          </div>
          @error('operateur')<p class="erreur" id="e-op">{{ $message }}</p>@enderror
        </fieldset>

        <div class="champ">
          <label for="telephone">Numéro avec lequel vous avez payé</label>
          <input id="telephone" name="telephone" type="tel" inputmode="tel" value="{{ old('telephone') }}"
                 placeholder="70 12 34 56" autocomplete="tel" required
                 @error('telephone') aria-invalid="true" aria-describedby="e-tel" @enderror>
          @error('telephone')<p class="erreur" id="e-tel">{{ $message }}</p>@enderror
        </div>

        <div class="champ">
          <label for="reference_transaction">Identifiant de transaction (reçu par SMS)</label>
          <input id="reference_transaction" name="reference_transaction" value="{{ old('reference_transaction') }}"
                 autocomplete="off" autocapitalize="characters" spellcheck="false" required
                 @error('reference_transaction') aria-invalid="true" aria-describedby="e-ref" @enderror>
          @error('reference_transaction')<p class="erreur" id="e-ref">{{ $message }}</p>@enderror
        </div>

        <button class="bouton" type="submit" id="envoyer">Envoyer mon inscription</button>
        <p class="note">Votre place est confirmée une fois votre paiement vérifié. Vous recevrez un email de confirmation.</p>
      </form>
      <script>
        var form = document.getElementById('form-inscription');
        var cases = form.querySelectorAll('input[name="formations[]"]');
        var total = document.getElementById('total');
        function maj() {
          var somme = 0;
          cases.forEach(function (c) { if (c.checked) somme += Number(c.dataset.prix); });
          total.textContent = somme > 0 ? somme.toLocaleString('fr-FR') + '\u00A0FCFA' : 'selon vos choix';
        }
        cases.forEach(function (c) { c.addEventListener('change', maj); });
        maj();
        form.addEventListener('submit', function () {
          var b = document.getElementById('envoyer'); b.disabled = true; b.textContent = 'Envoi en cours…';
        });
      </script>
    @endif
  </section>
</main>
</body>
</html>