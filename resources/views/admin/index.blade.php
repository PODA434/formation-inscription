@php
  $onglets = [
    'en_attente_verification' => 'À vérifier',
    'payee' => 'Validées',
    'refusee' => 'Refusées',
    'toutes' => 'Toutes',
  ];
  $nbPayees = $compte['payee'] ?? 0;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Administration – {{ config('formation.titre') }}</title>
  @include('inscription._styles')
</head>
<body>
<main class="admin">
  <h1>Inscriptions – {{ config('formation.titre') }}</h1>
  <p>
    {{ $nbPayees }} inscription(s) validée(s){{ $places ? ' sur '.$places.' places' : '' }} ·
    {{ $compte['en_attente_verification'] ?? 0 }} à vérifier ·
    <a href="{{ route('admin.inscriptions.export') }}">Télécharger les validées (CSV)</a>
  </p>

  @if (session('ok')) <div class="succes" role="status">{{ session('ok') }}</div> @endif
  @if ($errors->any()) <div class="alerte" role="alert">{{ $errors->first() }}</div> @endif

  <nav class="onglets" aria-label="Filtrer par statut">
    @foreach ($onglets as $cle => $libelle)
      <a href="{{ route('admin.inscriptions.index', ['statut' => $cle, 'q' => $q ?: null]) }}"
         @if ($statut === $cle) aria-current="page" @endif>{{ $libelle }}</a>
    @endforeach
  </nav>

  <form class="recherche" method="GET" action="{{ route('admin.inscriptions.index') }}">
    <input type="hidden" name="statut" value="{{ $statut }}">
    <input type="search" name="q" value="{{ $q }}" placeholder="Nom, email, téléphone ou identifiant de transaction" aria-label="Rechercher">
    <button type="submit">Rechercher</button>
  </form>

  <div class="tableau">
    <table>
      <thead>
        <tr><th>Reçue le</th><th>Participant</th><th>Paiement déclaré</th><th>Montant attendu</th><th>Statut</th><th>Action</th></tr>
      </thead>
      <tbody>
      @forelse ($inscriptions as $i)
        <tr>
          <td>{{ $i->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <strong>{{ $i->prenom }} {{ $i->nom }}</strong><br>
            {{ $i->email }}<br>{{ $i->telephone }}
            @if ($i->ville || $i->profession)<br><span class="opt">{{ collect([$i->ville, $i->profession])->filter()->join(' · ') }}</span>@endif
          </td>
          <td>{{ $i->libelleOperateur() }}<br><span class="id">{{ $i->reference_transaction }}</span></td>
          <td>
            {{ number_format($i->montant, 0, ',', "\u{00A0}") }}&nbsp;{{ $i->devise === 'XOF' ? 'FCFA' : $i->devise }}
            @if ($i->libelleFormations())<br><span class="opt">{{ $i->libelleFormations() }}</span>@endif
          </td>
          <td>
            <span class="badge {{ $i->statut === 'payee' ? 'payee' : ($i->statut === 'refusee' ? 'refusee' : '') }}">
              {{ ['en_attente_verification' => 'À vérifier', 'payee' => 'Validée', 'refusee' => 'Refusée'][$i->statut] ?? $i->statut }}
            </span>
            @if ($i->motif_refus)<br><span class="opt">{{ $i->motif_refus }}</span>@endif
          </td>
          <td>
            <div class="actions">
              @if ($i->statut !== 'payee')
                <form method="POST" action="{{ route('admin.inscriptions.valider', $i) }}"
                      onsubmit="return confirm('Confirmer que vous avez bien reçu {{ $i->montant }} FCFA (identifiant {{ $i->reference_transaction }}) ?')">
                  @csrf <button class="petit plein" type="submit">Valider</button>
                </form>
                <form method="POST" action="{{ route('admin.inscriptions.refuser', $i) }}">
                  @csrf
                  <input type="text" name="motif" placeholder="Motif (facultatif)" maxlength="200" aria-label="Motif du refus">
                  <button class="petit danger" type="submit">Refuser</button>
                </form>
              @else
                <span class="opt">Validée le {{ $i->valide_le?->format('d/m/Y H:i') }}</span>
                <a class="petit plein" href="{{ $i->lienWhatsapp() }}" target="_blank" rel="noopener noreferrer">Rappel WhatsApp</a>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6">Aucune inscription dans cette liste.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="pagination">{{ $inscriptions->links() }}</div>
</main>
</body>
</html>