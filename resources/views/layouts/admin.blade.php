<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>{{ $title ?? 'Админ-панель' }}</title>
<style>
:root{--red:#C8102E;--ink:#1F1A17;--muted:#7A6F66;--line:#E7DED2;--bg:#F6F1EA;--card:#fff;--chip:#F2E9DD;--ok:#1F8A4C}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.4 -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Noto Sans Armenian",Arial,sans-serif}
a{color:inherit}
button,input,select,textarea{font:inherit;color:inherit}
.top{background:var(--ink);color:#fff}
.top .in{max-width:1200px;margin:0 auto;padding:12px 16px;display:flex;align-items:center;gap:18px;flex-wrap:wrap}
.top b{font-size:16px}
.top nav{display:flex;gap:4px;flex:1}
.top nav a{text-decoration:none;padding:7px 12px;border-radius:8px;opacity:.8}
.top nav a.on,.top nav a:hover{background:rgba(255,255,255,.12);opacity:1}
.top form{margin:0}
.top .link{background:none;border:0;color:#fff;opacity:.7;cursor:pointer;padding:7px 4px;text-decoration:none}
.page{max-width:1200px;margin:0 auto;padding:20px 16px 80px}
h1{font-size:22px;margin:0 0 16px}
h2{font-size:16px;margin:0}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;border:0;border-radius:10px;padding:9px 14px;font-weight:600;cursor:pointer;background:var(--chip);text-decoration:none;white-space:nowrap}
.btn:hover{filter:brightness(.97)}
.btn.primary{background:var(--red);color:#fff}
.btn.dark{background:var(--ink);color:#fff}
.btn.danger{background:#fff;color:var(--red);border:1px solid #f0c9cf}
.btn.sm{padding:6px 10px;font-size:13px;border-radius:8px}
.btn.icon{padding:6px 9px}
.btn[disabled]{opacity:.5;cursor:default}
.card{background:var(--card);border-radius:14px;box-shadow:0 1px 2px rgba(0,0,0,.05)}
.grid2{display:grid;grid-template-columns:280px minmax(0,1fr);gap:18px;align-items:start}
@media(max-width:800px){.grid2{grid-template-columns:1fr}}
.cats{padding:8px}
.cat{display:flex;align-items:center;gap:6px;padding:8px 8px 8px 12px;border-radius:10px;cursor:pointer}
.cat:hover{background:var(--bg)}
.cat.on{background:var(--red);color:#fff}
.cat .n{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600}
.cat .c{font-size:12px;opacity:.7}
.cat.off .n{text-decoration:line-through;opacity:.6}
.head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 16px;border-bottom:1px solid var(--line);flex-wrap:wrap}
.row{display:flex;align-items:center;gap:12px;padding:10px 16px;border-bottom:1px solid var(--line)}
.row:last-child{border-bottom:0}
.row.off{opacity:.5}
.thumb{width:56px;height:56px;border-radius:10px;object-fit:cover;background:var(--chip);flex:none}
.row .t{flex:1;min-width:0}
.row .t b{display:block}
.row .t span{color:var(--muted);font-size:12.5px}
.price{font-weight:700;color:var(--red);white-space:nowrap}
.acts{display:flex;gap:4px;flex-wrap:wrap;justify-content:flex-end}
.tag{display:inline-block;background:var(--red);color:#fff;font-size:10px;font-weight:700;border-radius:5px;padding:1px 5px;margin-left:6px;vertical-align:2px}
.tag.gray{background:#999}
.empty{padding:30px;text-align:center;color:var(--muted)}
label.f{display:block;margin-bottom:12px}
label.f>span{display:block;font-size:12px;font-weight:600;color:var(--muted);margin-bottom:4px}
.inp{width:100%;border:1px solid var(--line);border-radius:10px;padding:9px 11px;background:#fff;outline:0}
.inp:focus{border-color:var(--ink)}
textarea.inp{min-height:62px;resize:vertical}
.cols3{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.cols2{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
@media(max-width:600px){.cols3{grid-template-columns:1fr}}
.check{display:flex;align-items:center;gap:8px;margin:6px 0;font-weight:600;cursor:pointer}
.err{color:var(--red);font-size:12px;margin-top:3px}
.modal-bg{position:fixed;inset:0;background:rgba(20,10,5,.5);z-index:50;display:flex;align-items:flex-start;justify-content:center;padding:30px 12px;overflow-y:auto}
.modal{background:#fff;border-radius:16px;width:min(760px,100%);box-shadow:0 20px 60px rgba(0,0,0,.3)}
.modal .mh{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--line)}
.modal .mb{padding:18px 20px}
.modal .mf{display:flex;justify-content:flex-end;gap:8px;padding:14px 20px;border-top:1px solid var(--line)}
.sec{font-size:12px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:var(--muted);margin:16px 0 8px}
.photo{display:flex;gap:14px;align-items:center}
.photo img,.photo .ph{width:120px;height:90px;border-radius:10px;object-fit:cover;background:var(--chip);flex:none}
.size-row{display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;margin-bottom:8px}
.flash{background:#E5F4EA;color:var(--ok);padding:10px 14px;border-radius:10px;margin-bottom:14px;font-weight:600}
.muted{color:var(--muted)}
</style>
@livewireStyles
</head>
<body>
@auth
<div class="top"><div class="in">
  <b>QR-меню</b>
  <nav>
    <a href="{{ route('admin.menu') }}" class="{{ request()->routeIs('admin.menu') ? 'on' : '' }}">Меню</a>
    <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'on' : '' }}">Настройки и QR</a>
  </nav>
  <a class="link" href="{{ route('menu') }}" target="_blank">Открыть сайт ↗</a>
  <form method="post" action="{{ route('logout') }}">@csrf<button class="link">Выйти</button></form>
</div></div>
@endauth
<div class="page">
{{ $slot }}
</div>
@livewireScripts
<script>
/* Resize a picked photo in the browser (max 1000px, WebP) so uploads stay small and need no server-side image library. */
window.resizePhoto = function (file, max = 1000) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => {
      const k = Math.min(1, max / Math.max(img.width, img.height));
      const c = document.createElement('canvas');
      c.width = Math.round(img.width * k); c.height = Math.round(img.height * k);
      c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
      let url = c.toDataURL('image/webp', 0.82);
      if (!url.startsWith('data:image/webp')) url = c.toDataURL('image/jpeg', 0.85);
      URL.revokeObjectURL(img.src);
      resolve(url);
    };
    img.onerror = reject;
    img.src = URL.createObjectURL(file);
  });
};
</script>
</body>
</html>
