(function(){
var root=document.documentElement;
function get(k){try{return localStorage.getItem(k)}catch(e){return null}}
function set(k,v){try{localStorage.setItem(k,v)}catch(e){}}
var I18N={
en:{n_services:"Services",n_check:"Health Check",n_clients:"Clients",n_about:"About",n_contact:"Contact",cta:"Book a Legacy Health Check",
cl_h:"What clients say",cl_p:"Placeholders. Real quotes appear only with each client's written consent.",
q_tag:"Placeholder",q1:"Quote from a long-term client, pending consent.",q2:"Quote from a second client, pending consent.",q3:"Quote from a third client, pending consent.",
case_h:"Case: Phalcon to Laravel",case_p:"How a hotel platform moved framework in planned steps, with an evening switch-over.",
f_line:"Scuttle Development. Legacy modernization, full-stack builds and security, Eindhoven.",f_rate:"EUR 90 per hour excl. VAT",f_demo:"Design demo. Not the live site."},
nl:{n_services:"Diensten",n_check:"Health Check",n_clients:"Klanten",n_about:"Over mij",n_contact:"Contact",cta:"Plan een Legacy Health Check",
cl_h:"Wat klanten zeggen",cl_p:"Plaatshouders. Echte quotes komen er alleen met schriftelijke toestemming van de klant.",
q_tag:"Plaatshouder",q1:"Quote van een langlopende klant, wacht op toestemming.",q2:"Quote van een tweede klant, wacht op toestemming.",q3:"Quote van een derde klant, wacht op toestemming.",
case_h:"Case: Phalcon naar Laravel",case_p:"Hoe een hotelplatform in geplande stappen van framework wisselde, met een overstap in de avond.",
f_line:"Scuttle Development. Legacy-modernisering, full-stack bouw en beveiliging, Eindhoven.",f_rate:"EUR 90 per uur excl. btw",f_demo:"Ontwerpdemo. Niet de live site."}};
var extra=window.I18N_EXTRA||{};
["en","nl"].forEach(function(l){Object.assign(I18N[l],extra[l]||{})});
var lang=get("sd-lang")==="nl"?"nl":"en";
root.dataset.theme=get("sd-theme")==="dark"?"dark":"light";
function apply(){
 document.querySelectorAll("[data-i]").forEach(function(e){var v=I18N[lang][e.dataset.i];if(v!=null)e.textContent=v});
 root.lang=lang;
 var b=document.getElementById("lang");if(b)b.textContent=lang==="en"?"NL":"EN";
 if(b)b.setAttribute("aria-label",lang==="en"?"Schakel naar Nederlands":"Switch to English");
}
document.addEventListener("DOMContentLoaded",function(){
 apply();
 var lb=document.getElementById("lang");if(lb)lb.addEventListener("click",function(){lang=lang==="en"?"nl":"en";set("sd-lang",lang);apply()});
 var tb=document.getElementById("theme");if(tb)tb.addEventListener("click",function(){var d=root.dataset.theme==="dark"?"light":"dark";root.dataset.theme=d;set("sd-theme",d)});
 var mb=document.getElementById("menu"),lk=document.querySelector(".links");
 if(mb&&lk){mb.addEventListener("click",function(){var o=lk.classList.toggle("open");mb.setAttribute("aria-expanded",o)});lk.addEventListener("click",function(e){if(e.target.tagName==="A"){lk.classList.remove("open");mb.setAttribute("aria-expanded","false")}})}
 var reduce=matchMedia("(prefers-reduced-motion: reduce)").matches;
 if(reduce||!window.gsap||!window.ScrollTrigger||!window.Lenis){root.classList.add("static");return}
 gsap.registerPlugin(ScrollTrigger);
 var lenis=new Lenis({lerp:0.12});
 lenis.on("scroll",ScrollTrigger.update);
 gsap.ticker.add(function(t){lenis.raf(t*1000)});
 gsap.ticker.lagSmoothing(0);
 document.querySelectorAll('a[href^="#"]').forEach(function(a){a.addEventListener("click",function(e){var t=document.querySelector(a.getAttribute("href"));if(t){e.preventDefault();lenis.scrollTo(t,{offset:-70})}})});
 if(window.setupMotion)window.setupMotion(lenis);
 window.addEventListener("load",function(){ScrollTrigger.refresh()});
});
})();
