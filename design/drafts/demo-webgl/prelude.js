function badge(t){var b=document.getElementById("mode");if(!b){b=document.createElement("div");b.id="mode";b.style.cssText="position:fixed;left:max(12px,env(safe-area-inset-left,0px));bottom:max(12px,env(safe-area-inset-bottom,0px));z-index:50;font:500 12px/1 monospace;background:#11111d;color:#fff;padding:7px 10px;border-radius:8px;opacity:.85";document.body.appendChild(b)}b.textContent=t}
function sstep(a,b,x){x=Math.max(0,Math.min(1,(x-a)/(b-a)));return x*x*(3-2*x)}
function bump(p,c,w){return Math.max(0,1-Math.abs(p-c)/w)}
function rnd(seed){var s=seed;return function(){s=(s*1664525+1013904223)%4294967296;return s/4294967296}}
window.setupMotion=function(){
 var canvas=document.getElementById("gl"),renderer;
 try{renderer=new THREE.WebGLRenderer({canvas:canvas,antialias:true,alpha:true});if(!renderer.getContext())throw 0}catch(e){badge("3D not available here: WebGL is off");document.documentElement.classList.add("static");return}
 badge("3D: WebGL on");
 renderer.setPixelRatio(Math.min(devicePixelRatio,2));
 var scene=new THREE.Scene(),cam=new THREE.PerspectiveCamera(36,1,.1,300);
 var S={p:0},c={p:0},mx=0,my=0;
 ScrollTrigger.create({trigger:"#story",start:"top top",end:"bottom bottom",onUpdate:function(s){S.p=s.progress}});
 addEventListener("pointermove",function(e){mx=e.clientX/innerWidth-.5;my=e.clientY/innerHeight-.5});
 var api=BUILD(scene,cam,renderer);
 function size(){var w=canvas.clientWidth,h=canvas.clientHeight;renderer.setSize(w,h,false);cam.aspect=w/h;cam.updateProjectionMatrix()}
 size();addEventListener("resize",size);
 function frame(t){c.p+=(S.p-c.p)*.1;api.update(c.p,t/1000,mx,my,cam.aspect);renderer.render(scene,cam);requestAnimationFrame(frame)}
 requestAnimationFrame(frame);
 gsap.utils.toArray(".act .box,.row,.q,.price").forEach(function(e){gsap.from(e,{y:36,opacity:0,duration:.7,ease:"power2.out",scrollTrigger:{trigger:e,start:"top 88%",once:true}})});
 window.__api=api;
};
