<!DOCTYPE html>
<html lang="{{ $s['default_lang'] }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="{{ $s['color'] }}">
<title>{{ $s['name'] }} — Menu</title>
<style>
@include('partials.menu-css')
</style>
</head>
<body>

<header class="hero">
  <div class="wrap">
    <div class="top">
      <span style="font-size:13px;opacity:.85" data-t="tag"></span>
      <div class="lang" id="lang">
        <button data-l="hy">ՀԱՅ</button><button data-l="ru">RU</button><button data-l="en">EN</button>
      </div>
    </div>
    <div class="brand">
      <div class="logo" aria-hidden="true">
        <svg viewBox="0 0 64 64"><path d="M32 6 4 52h56z" fill="{{ $s['color'] }}"/><circle cx="27" cy="36" r="4" fill="#fff"/><circle cx="37" cy="28" r="3" fill="#fff"/><circle cx="38" cy="42" r="3.5" fill="#fff"/></svg>
      </div>
      <div>
        <h1>{{ $s['name'] }}</h1>
        <p data-t="sub"></p>
      </div>
    </div>
  </div>
</header>

<div class="wrap search">
  <label>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
    <input id="q" type="search" autocomplete="off">
    <button class="clear" id="clear" aria-label="×">×</button>
  </label>
</div>

<div class="layout">
<nav class="cats"><div class="row" id="chips"></div></nav>
<div class="content">
<main class="wrap" id="menu"></main>
<div class="wrap empty" id="empty"></div>
</div>
</div>

<footer class="wrap" data-t="foot"></footer>

<div class="bar" id="bar"><button id="barBtn"><span><span class="cnt" id="barCnt">0</span><span data-t="mine"></span></span><span id="barSum"></span></button></div>

<div class="shade" id="shade"></div>
<div class="sheet" id="detail"></div>
<div class="sheet" id="order"></div>

<script>
const T={
 ru:{search:"Найти блюдо",nothing:"Ничего не нашли. Попробуйте другое название.",mine:"Мой выбор",add:"Добавить",added:"Добавлено ✓",
     oTitle:"Мой выбор",oSub:"Покажите этот список официанту",total:"Итого",clearAll:"Очистить список",close:"Закрыть",hit:"ХИТ",size:"Размер"},
 hy:{search:"Գտնել ուտեստ",nothing:"Ոչինչ չգտնվեց։ Փորձեք այլ անվանում։",mine:"Իմ ընտրությունը",add:"Ավելացնել",added:"Ավելացված է ✓",
     oTitle:"Իմ ընտրությունը",oSub:"Ցույց տվեք այս ցանկը մատուցողին",total:"Ընդամենը",clearAll:"Մաքրել ցանկը",close:"Փակել",hit:"ՀԻԹ",size:"Չափս"},
 en:{search:"Search dishes",nothing:"Nothing found. Try another name.",mine:"My selection",add:"Add",added:"Added ✓",
     oTitle:"My selection",oSub:"Show this list to your waiter",total:"Total",clearAll:"Clear list",close:"Close",hit:"HIT",size:"Size"}
};
/* Texts edited in the admin panel (Settings) */
const ST=@json($texts);
for(const l in ST) Object.assign(T[l],ST[l]);
const CUR=@json($s['currency']);
const MENU=@json($menu);

/* ---------- state ---------- */
const store={get(k,d){try{const v=localStorage.getItem(k);return v?JSON.parse(v):d}catch(e){return d}},
             set(k,v){try{localStorage.setItem(k,JSON.stringify(v))}catch(e){}}};
let lang=store.get('qr_lang',(()=>{const l=(navigator.language||'').slice(0,2);return ['hy','ru','en'].includes(l)?l:@json($s['default_lang'])})());
if(!T[lang]) lang='hy';
let order=store.get('qr_order',{});            // key -> qty
const sel={};                                   // item key -> chosen size index

const ITEMS={};
MENU.forEach(c=>c.items.forEach(it=>{it.key='i'+it.id;it.cat=c;ITEMS[it.key]=it;
  if(it.s.length) sel[it.key]=Math.floor((it.s.length-1)/2);}));   // middle size by default

/* ---------- helpers ---------- */
const $=s=>document.querySelector(s);
const fmt=n=>n.toLocaleString('ru-RU').replace(/\s/g,' ')+(CUR?' '+CUR:'');
const unit=w=>!w?'':lang==='hy'?w.replace('кг','կգ').replace('мл','մլ').replace(/ г$/,' գ')
              :lang==='en'?w.replace('кг','kg').replace('мл','ml').replace(/ г$/,' g').replace(',','.'):w;
const cm=s=>/^\d+([.,]\d+)?$/.test(s)?s+({hy:' սմ',en:' cm'}[lang]||' см'):s;
const nm=it=>it[lang]||it.ru;
const ds=it=>it['d'+lang]||it.dru||'';
const cur=it=>it.s.length?{p:it.s[sel[it.key]][1],w:it.s[sel[it.key]][2],size:it.s[sel[it.key]][0]}:{p:it.p,w:it.w};
const oKey=it=>it.s.length?it.key+'@'+cur(it).size:it.key;
const esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const priceOf=k=>{const [key,size]=k.split('@');const it=ITEMS[key];if(!it)return null;
  if(!it.s.length)return size===undefined?it.p:null;const s=it.s.find(x=>x[0]===size);return s?s[1]:null};

/* ---------- render ---------- */
function sizesHtml(it){
  if(!it.s.length) return '';
  if(it.s.length<2) return `<div class="sizes"><button class="on" disabled>${esc(cm(it.s[0][0]))}</button></div>`;
  return `<div class="sizes">${it.s.map((s,i)=>`<button data-size="${i}" data-k="${it.key}" class="${i===sel[it.key]?'on':''}">${esc(cm(s[0]))}</button>`).join('')}</div>`;
}
function cardHtml(it){
  const c=cur(it), inOrder=order[oKey(it)];
  return `<article class="card" data-k="${it.key}">
    <div class="ph${it.img?'':' noimg'}" data-open="${it.key}">${it.img?`<img loading="lazy" src="${it.img}" alt="">`:''}${it.hit?`<span class="badge">${T[lang].hit}</span>`:''}</div>
    <div class="body">
      <div class="name" data-open="${it.key}">${esc(nm(it))}</div>
      <div class="desc">${esc(ds(it))}</div>
      ${sizesHtml(it)}
      <div class="foot">
        <div><div class="price">${fmt(c.p)}</div><div class="w">${esc(unit(c.w))}</div></div>
        <button class="add ${inOrder?'in':''}" data-add="${it.key}" aria-label="${T[lang].add}">${inOrder?'×'+inOrder:'+'}</button>
      </div>
    </div></article>`;
}
function render(){
  document.documentElement.lang=lang;
  document.querySelectorAll('[data-t]').forEach(e=>e.innerHTML=T[lang][e.dataset.t]||'');
  document.querySelectorAll('#lang button').forEach(b=>b.classList.toggle('on',b.dataset.l===lang));
  $('#q').placeholder=T[lang].search;
  const q=$('#q').value.trim().toLowerCase();
  let shown=0;
  $('#menu').innerHTML=MENU.map(c=>{
    const list=c.items.filter(it=>!q||[it.ru,it.hy,it.en,it.dru,it.dhy,it.den].join(' ').toLowerCase().includes(q));
    shown+=list.length;
    return list.length?`<section id="${c.id}"><h2>${esc(nm(c))}</h2><div class="grid">${list.map(cardHtml).join('')}</div></section>`:'';
  }).join('');
  $('#chips').innerHTML=MENU.filter(c=>document.getElementById(c.id)).map(c=>`<button class="chip" data-go="${c.id}">${esc(nm(c))}</button>`).join('');
  $('#empty').style.display=shown?'none':'block'; $('#empty').textContent=T[lang].nothing;
  $('#clear').style.display=q?'block':'none';
  updateBar(); spy();
}
function refreshCard(key){
  const el=document.querySelector(`.card[data-k="${key}"]`); if(!el) return;
  el.outerHTML=cardHtml(ITEMS[key]);
}

/* ---------- order ---------- */
function save(){store.set('qr_order',order)}
function addItem(it){const k=oKey(it);order[k]=(order[k]||0)+1;save();refreshCard(it.key);updateBar();}
function updateBar(){
  let n=0,sum=0;
  for(const k in order){const p=priceOf(k);if(p===null){delete order[k];continue}n+=order[k];sum+=p*order[k];}
  $('#barCnt').textContent=n;$('#barSum').textContent=fmt(sum);
  $('#bar').classList.toggle('show',n>0);
  return sum;
}
function renderOrder(){
  const total=updateBar();
  const rows=Object.keys(order).map(k=>{
    const [key,size]=k.split('@');const it=ITEMS[key];
    return `<div class="o-item">${it.img?`<img src="${it.img}" alt="">`:'<img alt="">'}
      <div class="t"><b>${esc(nm(it))}</b><span>${size!==undefined?esc(cm(size))+' · ':''}${fmt(priceOf(k))}</span></div>
      <div class="qty"><button data-dec="${esc(k)}">−</button><b>${order[k]}</b><button data-inc="${esc(k)}">+</button></div></div>`;
  }).join('');
  $('#order').innerHTML=`<button class="x" data-close>×</button>
    <div class="o-head"><h3>${T[lang].oTitle}</h3><p>${T[lang].oSub}</p></div>
    <div class="o-list">${rows}</div>
    <div class="o-total"><span>${T[lang].total}</span><span>${fmt(total)}</span></div>
    <div class="o-actions"><button class="cta" data-close>${T[lang].close}</button><button class="ghost" data-clearall>${T[lang].clearAll}</button></div>`;
}

/* ---------- sheets ---------- */
let openSheet=null;
function show(id){openSheet=$(id);$('#shade').classList.add('show');openSheet.classList.add('show');document.body.style.overflow='hidden'}
function hide(){if(!openSheet)return;$('#shade').classList.remove('show');openSheet.classList.remove('show');openSheet=null;document.body.style.overflow=''}
function renderDetail(key){
  const it=ITEMS[key],c=cur(it);
  $('#detail').dataset.k=key;
  $('#detail').innerHTML=`<button class="x" data-close>×</button>
    <div class="d-img">${it.img?`<img src="${it.img}" alt="">`:''}</div>
    <div class="d-body"><h3>${esc(nm(it))}</h3><p class="dd">${esc(ds(it))}</p>
      ${it.s.length>1?`<div class="w" style="margin-bottom:6px">${T[lang].size}</div>`:''}${sizesHtml(it)}
      <div class="d-row"><span class="price">${fmt(c.p)}</span><span class="w">${esc(unit(c.w))}</span></div>
      <button class="cta" data-add="${key}" data-fromdetail>${T[lang].add} · ${fmt(c.p)}</button></div>`;
}

/* ---------- events ---------- */
document.addEventListener('click',e=>{
  const t=e.target.closest('button,[data-open]'); if(!t) return;
  const d=t.dataset;
  if(d.l){lang=d.l;store.set('qr_lang',lang);render();if(openSheet&&openSheet.id==='detail')renderDetail($('#detail').dataset.k);if(openSheet&&openSheet.id==='order')renderOrder();return}
  if(d.go){const s=document.getElementById(d.go);window.scrollTo({top:s.getBoundingClientRect().top+scrollY-(innerWidth>=1000?20:56),behavior:'smooth'});return}
  if(d.size!==undefined){sel[d.k]=+d.size;refreshCard(d.k);if(openSheet&&openSheet.id==='detail')renderDetail(d.k);return}
  if(d.add){addItem(ITEMS[d.add]);if(d.fromdetail!==undefined){t.textContent=T[lang].added;setTimeout(hide,450)}return}
  if(d.open){renderDetail(d.open);show('#detail');return}
  if(d.close!==undefined){hide();return}
  if(d.inc){order[d.inc]++;save();renderOrder();refreshCard(d.inc.split('@')[0]);return}
  if(d.dec){order[d.dec]--;if(order[d.dec]<=0)delete order[d.dec];save();refreshCard(d.dec.split('@')[0]);
    if(!Object.keys(order).length){hide();updateBar()}else renderOrder();return}
  if(d.clearall!==undefined){order={};save();hide();render();return}
});
$('#shade').addEventListener('click',hide);
$('#barBtn').addEventListener('click',()=>{renderOrder();show('#order')});
$('#q').addEventListener('input',render);
$('#clear').addEventListener('click',e=>{e.preventDefault();$('#q').value='';render()});
document.addEventListener('keydown',e=>{if(e.key==='Escape')hide()});

// Highlight the active category while scrolling
function spy(){
  const secs=[...document.querySelectorAll('main section')];let act=secs[0];
  for(const s of secs){if(s.getBoundingClientRect().top<120)act=s}
  document.querySelectorAll('.chip').forEach(ch=>{const on=act&&ch.dataset.go===act.id;
    if(on&&!ch.classList.contains('on')){ch.classList.add('on');if(innerWidth>=1000){const n=document.querySelector('.cats');const r=ch.getBoundingClientRect(),nr=n.getBoundingClientRect();if(r.top<nr.top||r.bottom>nr.bottom)n.scrollTo({top:n.scrollTop+r.top-nr.top-40,behavior:'smooth'});return}const row=$('#chips');row.scrollTo({left:row.scrollLeft+ch.getBoundingClientRect().left-row.getBoundingClientRect().left-16,behavior:'smooth'})}
    else if(!on)ch.classList.remove('on')});
}
let tick=false;window.addEventListener('scroll',()=>{if(!tick){tick=true;requestAnimationFrame(()=>{spy();tick=false})}},{passive:true});

render();
</script>
</body>
</html>
