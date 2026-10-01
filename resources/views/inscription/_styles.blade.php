<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
<style>
  /* ====== Palette : modifiez ces couleurs pour changer toute l'apparence ====== */
  :root{
    --encre:#14202B; --papier:#F6F4EE; --carte:#FFFFFF;
    --foret:#0F6B54; --foret-fonce:#0A4D3C; --menthe:#DDF3EA;
    --soleil:#F5A524; --soleil-clair:#FFF4DE; --ocre:#8A4B00;
    --corail:#E4572E; --ciel:#2563EB;
    --ligne:#D9DED9; --erreur:#B3261E; --doux:#5A6872; --ok:#0F6B54; --fond-ok:#E6F5EE;
  }
  *{box-sizing:border-box}
  html{-webkit-text-size-adjust:100%}
  body{margin:0;min-height:100vh;color:var(--encre);
    background:
      radial-gradient(900px 520px at 105% -10%,rgba(245,165,36,.20),transparent 60%),
      radial-gradient(800px 520px at -10% 110%,rgba(15,107,84,.16),transparent 60%),
      var(--papier);
    font-family:"Bricolage Grotesque",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
    font-size:17px;line-height:1.55}

  /* ====== Mise en page ====== */
  .page{max-width:1120px;margin:0 auto;padding:32px 20px 64px;display:grid;gap:28px;grid-template-columns:1fr;align-items:start}
  @media(min-width:900px){
    .page{grid-template-columns:5fr 6fr;gap:40px;padding-top:56px}
    .resume{position:sticky;top:32px}
  }
  h1{font-size:clamp(1.9rem,4.5vw,2.75rem);line-height:1.08;margin:0 0 16px;font-weight:800;letter-spacing:-.02em}

  /* ====== Panneau d'accueil (gauche) ====== */
  .resume{position:relative;overflow:hidden;color:#fff;border-radius:28px;padding:34px 30px;
    background:linear-gradient(155deg,#0A4D3C 0%,#0F6B54 60%,#14866A 100%);
    box-shadow:0 24px 50px -24px rgba(10,77,60,.7)}
  .resume::before,.resume::after{content:"";position:absolute;border-radius:50%;pointer-events:none}
  .resume::before{width:260px;height:260px;right:-90px;top:-90px;background:radial-gradient(circle,rgba(245,165,36,.55),transparent 70%)}
  .resume::after{width:220px;height:220px;left:-80px;bottom:-80px;background:radial-gradient(circle,rgba(228,87,46,.40),transparent 70%)}
  .resume>*{position:relative;z-index:1}
  .resume h1{color:#fff}
  .resume .intro{margin:0 0 24px;max-width:46ch;color:rgba(255,255,255,.88)}

  .etiquette{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;margin-bottom:18px;border-radius:999px;
    background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.25);font-size:.85rem;font-weight:500}
  .etiquette::before{content:"";width:9px;height:9px;border-radius:50%;background:var(--soleil);animation:pulse 2s infinite}
  @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(245,165,36,.7)}70%{box-shadow:0 0 0 9px rgba(245,165,36,0)}100%{box-shadow:0 0 0 0 rgba(245,165,36,0)}}

  .infos,.tarifs{margin:0;padding:0;list-style:none;display:grid;gap:10px}
  .infos li{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:16px;
    background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.18)}
  .infos small{display:block;font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.72)}
  .ico{flex:none;width:42px;height:42px;display:grid;place-items:center;border-radius:12px;background:rgba(255,255,255,.16);font-size:1.25rem}

  .sous-titre{margin:26px 0 12px;font-size:.85rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#FFD27A}
  .tarifs li{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:16px;background:#fff;color:var(--encre);
    border-left:6px solid var(--clair)}
  .tarifs .ico{background:var(--teinte)}
  .tarifs .nom{flex:1;font-weight:500;line-height:1.25}
  .tarifs .prix{font-weight:800;color:var(--ocre);white-space:nowrap}
  .hero-contact{margin:24px 0 0;font-size:.92rem;color:rgba(255,255,255,.82)}

  /* Couleur propre à chaque pack (1er, 2e, 3e…) */
  .pack-1{--accent:#E58A00;--clair:#F5A524;--teinte:#FFF1D6}
  .pack-2{--accent:#2563EB;--clair:#5B8DEF;--teinte:#E4EDFF}
  .pack-3{--accent:#E4572E;--clair:#F08060;--teinte:#FFE9E2}

  /* ====== Carte formulaire (droite) ====== */
  .carte{position:relative;overflow:hidden;background:var(--carte);border-radius:28px;padding:32px 30px;
    border:1px solid rgba(20,32,43,.06);box-shadow:0 24px 50px -28px rgba(20,32,43,.35)}
  .carte::before{content:"";position:absolute;inset:0 0 auto 0;height:6px;
    background:linear-gradient(90deg,var(--soleil),var(--corail) 50%,var(--foret))}
  .carte form{counter-reset:etape}
  .carte h2{display:flex;align-items:center;gap:12px;margin:34px 0 16px;font-size:1.2rem}
  .carte h2:first-of-type{margin-top:6px}
  .carte h2::before{counter-increment:etape;content:counter(etape);flex:none;width:32px;height:32px;border-radius:50%;
    display:grid;place-items:center;background:var(--soleil);color:#3A2500;font-size:.95rem;font-weight:700}

  .champ{margin-bottom:18px;border:0;padding:0;min-width:0}
  label,legend{display:block;font-weight:500;margin-bottom:6px;padding:0}
  .opt{color:var(--doux);font-weight:400}
  input[type=text],input[type=email],input[type=tel],input[type=search],input:not([type]){
    width:100%;font:inherit;padding:13px 15px;border:1.5px solid #B7C1BA;border-radius:12px;background:#FBFCFB;color:var(--encre);
    transition:border-color .15s,background .15s}
  input[type=text]:hover,input[type=email]:hover,input[type=tel]:hover,input[type=search]:hover,input:not([type]):hover{border-color:#8A968F}
  input[type=text]:focus,input[type=email]:focus,input[type=tel]:focus,input[type=search]:focus,input:not([type]):focus{border-color:var(--foret);background:#fff}
  input:focus-visible,button:focus-visible,a:focus-visible,select:focus-visible{outline:3px solid #2B7A67;outline-offset:2px}
  input[aria-invalid="true"]{border-color:var(--erreur)}
  .erreur{color:var(--erreur);font-size:.92rem;margin:6px 0 0}
  .alerte{border:1px solid var(--erreur);color:var(--erreur);border-radius:12px;padding:12px 14px;margin-bottom:20px;background:#FDF3F2}
  .succes{border:1px solid var(--ok);color:var(--ok);border-radius:12px;padding:12px 14px;margin-bottom:20px;background:var(--fond-ok)}
  .deux{display:grid;gap:0 16px;grid-template-columns:1fr}
  @media(min-width:520px){.deux{grid-template-columns:1fr 1fr}}
  .piege{position:absolute;left:-9999px;height:0;overflow:hidden}

  .bouton{display:block;width:100%;text-align:center;text-decoration:none;font:inherit;font-weight:700;font-size:1.05rem;cursor:pointer;
    color:#fff;border:0;border-radius:14px;padding:16px 20px;margin-top:10px;
    background:linear-gradient(135deg,#0F6B54,#14866A);box-shadow:0 12px 24px -12px rgba(15,107,84,.8);
    transition:transform .15s,filter .15s}
  .bouton::after{content:"\00a0→"}
  .bouton:hover{transform:translateY(-2px);filter:brightness(1.06)}
  .bouton[disabled]{opacity:.65;cursor:wait;transform:none}
  .bouton[disabled]::after{content:""}
  .note{color:var(--doux);font-size:.92rem;margin:14px 0 0;text-align:center}

  /* ====== Choix des formations ====== */
  .packs{display:grid;gap:12px;grid-template-columns:1fr}
  .packs label{display:flex;align-items:center;gap:14px;font-weight:500;margin:0;padding:14px 16px;background:#fff;
    border:1.5px solid #B7C1BA;border-radius:16px;cursor:pointer;transition:border-color .15s,background .15s,transform .15s}
  .packs label:hover{border-color:var(--accent);transform:translateY(-1px)}
  .packs .ico{background:var(--teinte)}
  .packs .texte{flex:1;min-width:0;line-height:1.3}
  .packs small{display:block;margin-top:2px;color:var(--doux);font-weight:400}
  .packs .prix-pack{font-weight:800;color:var(--ocre);white-space:nowrap}
  .packs input{appearance:none;-webkit-appearance:none;flex:none;display:grid;place-items:center;width:24px;height:24px;margin:0;
    border:2px solid #8A968F;border-radius:7px;background:#fff;cursor:pointer}
  .packs input:checked{background:var(--accent);border-color:var(--accent)}
  .packs input:checked::after{content:"";width:6px;height:11px;border:solid #fff;border-width:0 2.5px 2.5px 0;transform:rotate(45deg) translate(-1px,-1px)}
  .packs label:has(input:checked){border-color:var(--accent);background:var(--teinte);box-shadow:0 0 0 1px var(--accent)}
  @media(max-width:480px){.packs .ico{display:none}}

  /* ====== Instructions de paiement ====== */
  .paiement{border:1.5px solid var(--ligne);border-radius:16px;margin-bottom:14px;overflow:hidden}
  .paiement div{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:13px 16px;border-bottom:1px solid var(--ligne)}
  .paiement div:last-child{border-bottom:0}
  .paiement .total{background:linear-gradient(90deg,var(--soleil-clair),#fff)}
  .paiement .total .num{font-size:1.35rem;color:var(--ocre)}
  .paiement .num{font-weight:700;font-size:1.1rem;letter-spacing:.02em;user-select:all}
  .paiement .orange>span:first-child::before,.paiement .moov>span:first-child::before{content:"";display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:9px}
  .paiement .orange>span:first-child::before{background:#FF7900}
  .paiement .moov>span:first-child::before{background:#0A6CC4}
  .etapes{margin:0;padding-left:1.3em;color:var(--doux)}
  .etapes li{margin-bottom:6px;padding-left:4px}
  .etapes li::marker{color:var(--foret);font-weight:700}

  /* ====== Choix de l'opérateur ====== */
  .choix{display:grid;gap:10px;grid-template-columns:1fr}
  @media(min-width:520px){.choix{grid-template-columns:1fr 1fr}}
  .choix label{display:flex;align-items:center;gap:10px;font-weight:500;margin:0;padding:13px 15px;background:#fff;
    border:1.5px solid #B7C1BA;border-radius:12px;cursor:pointer;transition:border-color .15s,background .15s}
  .choix label:hover{border-color:var(--foret)}
  .choix input{width:auto;margin:0;accent-color:var(--foret)}
  .choix label:has(input:checked){border-color:var(--foret);background:var(--fond-ok);box-shadow:0 0 0 1px var(--foret)}

  /* ====== Page de statut ====== */
  .statut{max-width:600px;margin:0 auto;padding:48px 20px 64px}
  .statut-carte{position:relative;overflow:hidden;background:#fff;border-radius:28px;padding:44px 32px 32px;text-align:center;
    border:1px solid rgba(20,32,43,.06);box-shadow:0 24px 50px -28px rgba(20,32,43,.35)}
  .statut-carte::before{content:"";position:absolute;inset:0 0 auto 0;height:6px;
    background:linear-gradient(90deg,var(--soleil),var(--corail) 50%,var(--foret))}
  .statut h1{font-size:clamp(1.6rem,4vw,2.1rem);margin-bottom:12px}
  .statut p{margin:0 0 14px;color:var(--doux)}
  .pastille{width:76px;height:76px;margin:0 auto 20px;border-radius:50%;display:grid;place-items:center;font-size:2rem;font-weight:700}
  .pastille.ok{background:var(--fond-ok);color:var(--ok)}
  .pastille.attente{background:var(--soleil-clair);color:var(--ocre)}
  .pastille.refus{background:#FDECE8;color:var(--erreur)}
  .statut dl{margin:24px 0;padding:18px 20px;text-align:left;background:#F7F8F6;border-radius:16px;display:grid;grid-template-columns:auto 1fr;gap:10px 20px}
  .statut dt{color:var(--doux)} .statut dd{margin:0;font-weight:500;overflow-wrap:anywhere}

  /* ====== Administration ====== */
  .admin{max-width:1180px;margin:0 auto;padding:32px 20px 64px}
  .admin h1{font-size:1.7rem;margin-bottom:8px}
  .onglets{display:flex;flex-wrap:wrap;gap:8px;margin:20px 0}
  .onglets a{padding:8px 16px;border:1px solid var(--ligne);border-radius:999px;text-decoration:none;color:var(--encre);background:#fff}
  .onglets a:hover{border-color:var(--foret)}
  .onglets a[aria-current="page"]{background:var(--foret);border-color:var(--foret);color:#fff}
  .recherche{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap}
  .recherche input{flex:1;min-width:220px}
  .recherche button,.petit{display:inline-block;text-decoration:none;text-align:center;font:inherit;font-weight:500;
    border:1px solid var(--foret);background:#fff;color:var(--foret);border-radius:10px;padding:8px 14px;cursor:pointer}
  .petit.plein,.recherche button{background:var(--foret);color:#fff}
  .petit.danger{border-color:var(--erreur);color:var(--erreur)}
  .tableau{overflow-x:auto;background:#fff;border:1px solid var(--ligne);border-radius:16px;box-shadow:0 12px 30px -20px rgba(20,32,43,.3)}
  table{border-collapse:collapse;width:100%;min-width:860px;font-size:.95rem}
  th,td{text-align:left;padding:12px 14px;border-bottom:1px solid var(--ligne);vertical-align:top}
  th{font-weight:700;font-size:.78rem;letter-spacing:.05em;text-transform:uppercase;color:var(--doux);background:#F3F6F3}
  tbody tr:hover{background:#FAFBFA}
  td .id{font-weight:700;user-select:all;overflow-wrap:anywhere}
  .actions{display:flex;flex-direction:column;gap:6px;min-width:190px}
  .actions form{display:flex;gap:6px}
  .actions input[type=text]{padding:6px 8px;font-size:.9rem;border-radius:8px}
  .badge{display:inline-block;padding:3px 10px;border-radius:999px;font-size:.85rem;font-weight:500;background:var(--soleil-clair);color:var(--ocre)}
  .badge.payee{background:var(--fond-ok);color:var(--ok)} .badge.refusee{background:#FDF3F2;color:var(--erreur)}
  .pagination{margin-top:16px}

  /* ====== Animations (désactivées si l'utilisateur les refuse) ====== */
  .resume,.carte,.statut-carte{animation:monte .5s ease both}
  @keyframes monte{from{opacity:0;transform:translateY(14px)}}
  @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>