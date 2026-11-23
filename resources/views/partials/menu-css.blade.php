:root{
  --red:{{ $s['color'] }}; --cream:#FBF6EF; --card:#fff; --ink:#1F1A17; --muted:#7A6F66;
  --line:#EDE4D8; --chip:#F2E9DD; --radius:18px;
}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{margin:0;background:var(--cream);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Noto Sans Armenian","Noto Sans",Arial,sans-serif;font-size:15px;line-height:1.35}
button{font:inherit;color:inherit;border:0;background:none;cursor:pointer;padding:0}
.wrap{max-width:760px;margin:0 auto;padding:0 16px}

/* Header */
.hero{background:var(--red);color:#fff;padding:18px 0 54px;position:relative;overflow:hidden}
.hero::after{content:"";position:absolute;right:-60px;top:-40px;width:220px;height:220px;border-radius:50%;
  background:radial-gradient(circle at 30% 30%,rgba(255,255,255,.16),rgba(255,255,255,0) 70%);pointer-events:none}
.hero .wrap{position:relative;z-index:1}
.top{display:flex;justify-content:space-between;align-items:center}
.lang{display:flex;background:rgba(0,0,0,.18);border-radius:999px;padding:3px}
.lang button{padding:6px 12px;border-radius:999px;font-size:13px;font-weight:600;color:rgba(255,255,255,.8)}
.lang button.on{background:#fff;color:var(--red)}
.brand{margin-top:26px;display:flex;align-items:center;gap:14px}
.logo{width:58px;height:58px;border-radius:16px;background:#fff;display:grid;place-items:center;flex:none}
.logo svg{width:36px;height:36px}
.brand h1{margin:0;font-size:30px;letter-spacing:-.5px;line-height:1}
.brand p{margin:6px 0 0;opacity:.85;font-size:14px}

/* Search */
.search{margin-top:-30px;position:relative;z-index:2}
.search label{display:flex;align-items:center;gap:10px;background:#fff;border-radius:16px;padding:0 16px;height:52px;
  box-shadow:0 6px 20px rgba(60,20,10,.10)}
.search input{border:0;outline:0;flex:1;font:inherit;font-size:16px;background:none;color:var(--ink)}
.search svg{flex:none;opacity:.5}
.search input::-webkit-search-cancel-button{display:none;-webkit-appearance:none}
.clear{display:none;width:26px;height:26px;border-radius:50%;background:var(--chip);font-size:16px;line-height:26px;text-align:center}

/* Category chips */
.cats{position:sticky;top:0;z-index:5;background:var(--cream);margin-top:14px;border-bottom:1px solid var(--line)}
.cats .row{display:flex;gap:8px;overflow-x:auto;padding:10px 16px;scrollbar-width:none;max-width:760px;margin:0 auto}
.cats .row::-webkit-scrollbar{display:none}
.chip{flex:none;padding:9px 15px;border-radius:999px;background:var(--chip);font-weight:600;font-size:14px;white-space:nowrap;transition:.15s}
.chip.on{background:var(--red);color:#fff}

/* Sections */
section{padding-top:22px;scroll-margin-top:64px}
section h2{margin:0 0 12px;font-size:13px;letter-spacing:1.4px;text-transform:uppercase;color:var(--muted)}
.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
@media(min-width:620px){.grid{grid-template-columns:repeat(3,1fr)}}
.card{background:var(--card);border-radius:var(--radius);overflow:hidden;display:flex;flex-direction:column;
  box-shadow:0 1px 2px rgba(60,20,10,.06);text-align:left}
.ph{aspect-ratio:1/1;background:#F4ECE2 center/cover no-repeat;position:relative}
.ph.noimg::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 50% 50%,rgba(0,0,0,.05),transparent 60%)}
.ph img{width:100%;height:100%;object-fit:cover;display:block}
.badge{position:absolute;left:8px;top:8px;background:var(--red);color:#fff;font-size:11px;font-weight:700;padding:4px 8px;border-radius:8px;letter-spacing:.5px}
.body{padding:10px 12px 12px;display:flex;flex-direction:column;flex:1}
.name{font-weight:650;font-size:15px;line-height:1.25;margin:0 0 4px}
.desc{color:var(--muted);font-size:12.5px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:8px}
.sizes{display:flex;gap:4px;margin-bottom:8px}
.sizes button{flex:1;font-size:12px;font-weight:600;padding:5px 0;border-radius:8px;background:var(--chip);color:var(--muted);white-space:nowrap;min-width:0}
@media(max-width:359px){.wrap{padding:0 12px}.grid{gap:8px}.body{padding:9px 9px 10px}.sizes{gap:3px}.sizes button{font-size:11px;letter-spacing:-.2px}
  .name{font-size:14px}.price{font-size:15px}.add{width:32px;height:32px}.brand h1{font-size:26px}}
.sizes button.on{background:var(--ink);color:#fff}
.foot{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:6px}
.price{font-weight:750;font-size:16px;color:var(--red)}
.w{font-size:12px;color:var(--muted);font-weight:500}
.add{width:36px;height:36px;border-radius:50%;border:1.5px solid var(--red);color:var(--red);font-size:22px;line-height:1;display:grid;place-items:center;flex:none;transition:.15s}
.add.in{background:var(--red);color:#fff;font-size:14px;font-weight:700}
.empty{padding:40px 0;text-align:center;color:var(--muted);display:none}

footer{padding:34px 0 120px;text-align:center;color:var(--muted);font-size:13px}

/* Floating bar */
.bar{position:fixed;left:0;right:0;bottom:0;padding:12px 16px calc(12px + env(safe-area-inset-bottom));z-index:20;
  transform:translateY(120%);transition:transform .25s}
.bar.show{transform:none}
.bar button{width:100%;max-width:728px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;
  background:var(--ink);color:#fff;border-radius:16px;padding:15px 18px;font-weight:650;box-shadow:0 8px 24px rgba(0,0,0,.25)}
.bar .cnt{background:var(--red);border-radius:999px;padding:2px 9px;margin-right:8px;font-size:13px}

/* Sheets */
.shade{position:fixed;inset:0;background:rgba(20,10,5,.5);opacity:0;pointer-events:none;transition:.2s;z-index:30}
.shade.show{opacity:1;pointer-events:auto}
.sheet{position:fixed;left:0;right:0;bottom:0;z-index:31;background:#fff;border-radius:22px 22px 0 0;max-height:92vh;overflow-y:auto;
  transform:translateY(105%);transition:transform .28s cubic-bezier(.2,.8,.2,1);max-width:760px;margin:0 auto;padding-bottom:env(safe-area-inset-bottom)}
.sheet.show{transform:none}
.x{position:sticky;top:10px;float:right;margin:10px 10px 0 0;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.92);
  box-shadow:0 1px 6px rgba(0,0,0,.15);font-size:20px;z-index:2}
.d-img{aspect-ratio:4/3;background:#fff;margin-top:-46px}
.d-img img{width:100%;height:100%;object-fit:cover;display:block}
.d-body{padding:18px 20px 24px}
.d-body h3{margin:0 0 6px;font-size:22px}
.d-body .dd{color:var(--muted);margin:0 0 16px}
.d-body .sizes button{padding:10px 0;font-size:14px}
.d-row{display:flex;align-items:center;justify-content:space-between;margin:14px 0 18px}
.d-row .price{font-size:22px}
.cta{width:100%;background:var(--red);color:#fff;border-radius:14px;padding:15px;font-weight:700;font-size:16px}
.o-head{padding:20px 20px 6px}
.o-head h3{margin:0;font-size:22px}
.o-head p{margin:4px 0 0;color:var(--muted);font-size:13px}
.o-list{padding:6px 20px}
.o-item{display:flex;gap:12px;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)}
.o-item img{width:54px;height:54px;border-radius:12px;object-fit:cover;background:#F4ECE2;flex:none}
.o-item .t{flex:1;min-width:0}
.o-item .t b{display:block;font-weight:650;font-size:14.5px}
.o-item .t span{color:var(--muted);font-size:12.5px}
.qty{display:flex;align-items:center;gap:8px}
.qty button{width:30px;height:30px;border-radius:50%;background:var(--chip);font-size:18px;font-weight:600}
.qty b{min-width:16px;text-align:center}
.o-total{display:flex;justify-content:space-between;padding:16px 20px;font-size:18px;font-weight:750}
.o-actions{padding:0 20px 22px;display:flex;flex-direction:column;gap:10px}
.ghost{background:none;color:var(--muted);font-weight:600;padding:8px}

/* ---------- Desktop: horizontal layout ---------- */
@media(min-width:1000px){
  .wrap{max-width:1240px;padding:0 32px}
  .hero{padding:26px 0 70px}
  .brand{margin-top:22px;gap:18px}
  .logo{width:72px;height:72px;border-radius:20px}.logo svg{width:44px;height:44px}
  .brand h1{font-size:44px}.brand p{font-size:16px}
  .search{margin-top:-30px}
  .search label{max-width:560px;margin-left:calc(230px + 32px)}
  .layout{display:grid;grid-template-columns:230px minmax(0,1fr);gap:32px;max-width:1240px;margin:0 auto;padding:0 32px;align-items:start}
  .layout .wrap{max-width:none;padding:0}
  .cats{position:sticky;top:20px;margin-top:22px;background:none;border:0;max-height:calc(100vh - 40px);overflow-y:auto;scrollbar-width:thin}
  .cats .row{flex-direction:column;overflow:visible;padding:0;gap:4px;margin:0}
  .chip{text-align:left;border-radius:12px;padding:11px 14px;background:none;font-size:15px}
  .chip:hover{background:var(--chip)}
  .chip.on{background:var(--red);color:#fff}
  section{padding-top:26px;scroll-margin-top:20px}
  section h2{font-size:22px;letter-spacing:0;text-transform:none;color:var(--ink);margin-bottom:16px}
  .grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
  .card{transition:transform .15s,box-shadow .15s}
  .card:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(60,20,10,.12)}
  .ph,.name{cursor:pointer}
  footer{padding-bottom:60px}
  .bar{left:auto;right:28px;bottom:24px;padding:0;width:380px}
  .bar:not(.show){transform:translateY(calc(100% + 60px))}
  .sheet{left:50%;right:auto;top:50%;bottom:auto;width:min(880px,92vw);max-height:86vh;border-radius:22px;
    transform:translate(-50%,-46%);opacity:0;pointer-events:none;transition:transform .22s,opacity .22s;margin:0}
  .sheet.show{transform:translate(-50%,-50%);opacity:1;pointer-events:auto}
  #detail{display:grid;grid-template-columns:1.1fr 1fr;overflow:hidden}
  #detail .x{position:absolute;top:14px;right:14px;margin:0;float:none}
  #detail .d-img{margin:0;aspect-ratio:auto;min-height:420px}
  #detail .d-body{padding:36px 32px;display:flex;flex-direction:column;justify-content:center}
  #detail .d-body h3{font-size:28px}
  #order{width:min(540px,92vw)}
}
@media(min-width:1440px){.grid{grid-template-columns:repeat(5,minmax(0,1fr))}.wrap,.layout{max-width:1440px}}
