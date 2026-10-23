function badge(t){var b=document.getElementById("mode");if(!b){b=document.createElement("div");b.id="mode";b.style.cssText="position:fixed;left:max(12px,env(safe-area-inset-left,0px));bottom:max(12px,env(safe-area-inset-bottom,0px));z-index:50;font:500 12px/1 monospace;background:#11111d;color:#fff;padding:7px 10px;border-radius:8px;opacity:.85";document.body.appendChild(b)}b.textContent=t}
var TEX="data:image/jpeg;base64,@@TEX@@",TW=1024,TH=3200;
var cssDone=false;
function cssFallback(label){
 if(cssDone)return;cssDone=true;badge(label);
 var st=document.querySelector(".stage");st.innerHTML='<div class="c3"><div class="lap" id="lap"><div class="scr" id="scr"></div></div></div>';
 document.getElementById("scr").style.backgroundImage="url("+TEX+")";
 var tl=gsap.timeline({defaults:{ease:"none"},scrollTrigger:{trigger:"#story",start:"top top",end:"bottom bottom",scrub:.8}});
 tl.fromTo("#lap",{rotationX:70,rotationY:-35,scale:.8},{rotationX:8,rotationY:0,scale:1,duration:1},0)
   .fromTo("#scr",{backgroundPosition:"50% 0%"},{backgroundPosition:"50% 100%",duration:2},1)
   .to("#lap",{scale:1.5,duration:1},3);
}
window.setupMotion=function(){
 var canvas=document.getElementById("gl"),renderer;
 try{renderer=new THREE.WebGLRenderer({canvas:canvas,antialias:true,alpha:true});if(!renderer.getContext())throw 0}catch(e){cssFallback("CSS 3D (WebGL not available here)");return}
 canvas.addEventListener("webglcontextlost",function(){cssFallback("CSS 3D (WebGL lost)")});
 badge("3D: WebGL on");
 renderer.setPixelRatio(Math.min(devicePixelRatio,2));
 var scene=new THREE.Scene(),cam=new THREE.PerspectiveCamera(34,1,.1,100);
 scene.add(new THREE.HemisphereLight(0xdfe2ff,0x20203a,1.0));
 var key=new THREE.DirectionalLight(0xffffff,1.2);key.position.set(3,6,5);scene.add(key);
 var rim=new THREE.DirectionalLight(0x7f7fff,1.1);rim.position.set(-6,3,-5);scene.add(rim);
 function rr(w,h,r){var s=new THREE.Shape(),x=-w/2,y=-h/2;s.moveTo(x+r,y);s.lineTo(x+w-r,y);s.quadraticCurveTo(x+w,y,x+w,y+r);s.lineTo(x+w,y+h-r);s.quadraticCurveTo(x+w,y+h,x+w-r,y+h);s.lineTo(x+r,y+h);s.quadraticCurveTo(x,y+h,x,y+h-r);s.lineTo(x,y+r);s.quadraticCurveTo(x,y,x+r,y);return s}
 function slab(w,h,d,r){var g=new THREE.ExtrudeGeometry(rr(w,h,r),{depth:d,bevelEnabled:true,bevelSize:.02,bevelThickness:.02,bevelSegments:2,curveSegments:10});g.translate(0,0,-d/2);return g}
 var alu=new THREE.MeshStandardMaterial({color:0xc9ccd8,metalness:.55,roughness:.38});
 var L=new THREE.Group();scene.add(L);
 // base: lies flat (extruded in XY, rotate to XZ)
 var base=new THREE.Mesh(slab(3.4,2.3,.14,.14),alu);base.rotation.x=-Math.PI/2;base.position.y=.07;L.add(base);
 // keyboard deck texture
 var kc=document.createElement("canvas");kc.width=1024;kc.height=690;var k=kc.getContext("2d");
 k.fillStyle="#1a1b26";k.fillRect(0,0,1024,690);
 var rows=[14,14,13,12,11],y0=60;
 rows.forEach(function(n,ri){var kw=(1024-120-(n-1)*8)/n;for(var i=0;i<n;i++){k.fillStyle="#2a2c3d";var x=60+i*(kw+8);k.beginPath();k.roundRect?k.roundRect(x,y0+ri*74,kw,64,8):k.rect(x,y0+ri*74,kw,64);k.fill()}});
 k.fillStyle="#2a2c3d";k.fillRect(300,y0+5*74,424,64);
 k.strokeStyle="#3a3d55";k.lineWidth=3;k.strokeRect(372,520,280,140);
 var ktex=new THREE.CanvasTexture(kc);
 var deck=new THREE.Mesh(new THREE.PlaneGeometry(3.2,2.14),new THREE.MeshStandardMaterial({map:ktex,roughness:.7,metalness:.1}));deck.rotation.x=-Math.PI/2;deck.position.set(0,.152,0);L.add(deck);
 // lid on a hinge at the back edge
 var hinge=new THREE.Group();hinge.position.set(0,.16,-1.1);L.add(hinge);
 var lid=new THREE.Mesh(slab(3.4,2.3,.08,.14),alu);lid.position.set(0,1.15,0);hinge.add(lid);
 var bez=new THREE.Mesh(new THREE.PlaneGeometry(3.24,2.14),new THREE.MeshBasicMaterial({color:0x08080e}));bez.position.set(0,1.15,.062);hinge.add(bez);
 var tex=new THREE.TextureLoader().load(TEX);tex.minFilter=THREE.LinearFilter;tex.generateMipmaps=false;tex.wrapS=tex.wrapT=THREE.ClampToEdgeWrapping;
 var vis=(TW*10/16)/TH;tex.repeat.set(1,vis);tex.offset.set(0,1-vis);
 var screen=new THREE.Mesh(new THREE.PlaneGeometry(3.12,1.95),new THREE.MeshBasicMaterial({map:tex}));screen.position.set(0,1.15,.064);hinge.add(screen);
 // soft ground disc
 var floor=new THREE.Mesh(new THREE.CircleGeometry(6,64),new THREE.MeshBasicMaterial({color:0x1a1a33,transparent:true,opacity:.55}));floor.rotation.x=-Math.PI/2;floor.position.y=-.01;scene.add(floor);
 var S={open:0,rot:-.9,tilt:0,cd:11,cy:5,cx:0,ty:0,scroll:0,fade:1,lx:0};
 var tl=gsap.timeline({defaults:{ease:"none"},scrollTrigger:{trigger:"#story",start:"top top",end:"bottom bottom",scrub:.9}});
 tl.to(S,{open:1,rot:-.25,duration:1.1},.1)
   .to(S,{cd:7.2,cy:2.4,ty:.9,duration:1.1},.1)
   .to(S,{rot:0,cd:4.6,cy:1.8,ty:1.15,duration:1},1.2)
   .to(S,{scroll:1,duration:2},2.0)
   .to(S,{cd:2.55,cy:1.3,ty:1.2,duration:1},3.0)
   .to(S,{fade:0,duration:.6},3.5);
 var mx=0,my=0;addEventListener("pointermove",function(e){mx=e.clientX/innerWidth-.5;my=e.clientY/innerHeight-.5});
 function size(){var w=canvas.clientWidth,h=canvas.clientHeight;renderer.setSize(w,h,false);cam.aspect=w/h;cam.updateProjectionMatrix()}
 size();addEventListener("resize",size);
 var c={open:0,rot:-.9,cd:11,cy:5,ty:0,scroll:0,fade:1};
 function frame(){
  for(var q in c)c[q]+=(S[q]-c[q])*.12;
  var asp=cam.aspect,wide=asp>1;
  var open=c.open*1.85;hinge.rotation.x=Math.PI/2-open;
  L.rotation.y=c.rot+mx*.35;L.rotation.x=my*.08;
  var shift=wide?(1-c.scroll*.0)*(1.0-Math.min(1,c.cd<5?1:0)*1.0):0;
  L.position.x=wide?(c.cd>7.2?1.5-3.1*Math.min(1,(11-c.cd)/3.8):-1.6*Math.max(0,(c.cd-4.6)/2.6)):0;
  L.position.y=wide?0:-.3;
  var dist=c.cd*(wide?1:1.35);
  cam.position.set(0,c.cy,dist);cam.lookAt(L.position.x,c.ty*(wide?1:.9),0);
  tex.offset.y=(1-vis)*(1-c.scroll);
  canvas.style.opacity=Math.max(0,Math.min(1,c.fade));
  renderer.render(scene,cam);requestAnimationFrame(frame)}
 frame();
 gsap.utils.toArray(".act .box,.row,.q,.price").forEach(function(e){gsap.from(e,{y:36,opacity:0,duration:.7,ease:"power2.out",scrollTrigger:{trigger:e,start:"top 88%",once:true}})});
};
