function badge(t){var b=document.getElementById("mode");if(!b){b=document.createElement("div");b.id="mode";b.style.cssText="position:fixed;left:max(12px,env(safe-area-inset-left,0px));bottom:max(12px,env(safe-area-inset-bottom,0px));z-index:50;font:500 12px/1 monospace;background:#11111d;color:#fff;padding:7px 10px;border-radius:8px;opacity:.85";document.body.appendChild(b)}b.textContent=t}
var cssDone=false;
function cssFallback(label){
 if(cssDone)return;cssDone=true;badge(label);
 var st=document.querySelector(".stage");st.innerHTML="";var logo=document.querySelector(".brand img").src;
 var c=document.createElement("div");c.className="c3";
 c.innerHTML='<div class="c3g" id="c3logo"><div class="face"><img alt="" src="'+logo+'"></div><div class="face back"><img alt="" src="'+logo+'"></div></div><div class="c3g" id="c3stack"><div class="sl"></div><div class="sl"></div><div class="sl"></div><div class="sl"></div></div>';
 st.appendChild(c);
 var wide=innerWidth>760,sl=gsap.utils.toArray("#c3stack .sl");
 gsap.set("#c3logo",{x:wide?innerWidth*.22:0,y:wide?0:-innerHeight*.2});
 gsap.set("#c3stack",{opacity:0,x:wide?-innerWidth*.22:0,y:wide?0:-innerHeight*.2});
 sl.forEach(function(e,i){gsap.set(e,{z:i*14,backgroundColor:i?"#c0392b":"#5454d4"})});
 var tl=gsap.timeline({defaults:{ease:"none"},scrollTrigger:{trigger:"#story",start:"top top",end:"bottom bottom",scrub:.8}});
 tl.to("#c3logo",{rotationY:540,rotationX:20,duration:1},0)
   .to("#c3logo",{opacity:0,scale:.4,duration:.5},.9)
   .to("#c3stack",{opacity:1,duration:.4},1.1)
   .to("#c3stack",{x:wide?innerWidth*.22:0,rotationZ:40,duration:2},1.1);
 sl.forEach(function(e,i){tl.to(e,{z:i*90,duration:1},1.1);tl.to(e,{backgroundColor:"#5454d4",duration:.6},2.2+i*.2)});
 tl.to(sl,{z:function(i){return i*18},duration:.8},3.1);
}
window.setupMotion=function(){
 var canvas=document.getElementById("gl");
 var renderer;try{renderer=new THREE.WebGLRenderer({canvas:canvas,antialias:true,alpha:true});if(!renderer.getContext())throw 0}catch(e){cssFallback("CSS 3D (WebGL not available here)");return}
 canvas.addEventListener("webglcontextlost",function(){cssFallback("CSS 3D (WebGL lost)")});badge("3D: WebGL on");
 renderer.setPixelRatio(Math.min(devicePixelRatio,2));
 var scene=new THREE.Scene(),cam=new THREE.PerspectiveCamera(38,1,.1,100);
 scene.add(new THREE.HemisphereLight(0xffffff,0x8888aa,.9));
 var key=new THREE.DirectionalLight(0xffffff,1.1);key.position.set(4,6,6);scene.add(key);
 var rim=new THREE.DirectionalLight(0x8e8eff,.8);rim.position.set(-5,-2,-4);scene.add(rim);
 var INDIGO=new THREE.Color(0x5454d4),RED=new THREE.Color(0xc0392b);
 // gear
 function gearShape(teeth,ro,ri,hole){var s=new THREE.Shape(),n=teeth*4,i,a,r,x,y;
  for(i=0;i<n;i++){a=i/n*Math.PI*2;r=(i%4<2)?ro:ri;x=Math.cos(a)*r;y=Math.sin(a)*r;if(i)s.lineTo(x,y);else s.moveTo(x,y)}
  s.closePath();var h=new THREE.Path();h.absarc(0,0,hole,0,Math.PI*2,true);s.holes.push(h);return s}
 var gg=new THREE.ExtrudeGeometry(gearShape(14,2.05,1.7,1.05),{depth:.55,bevelEnabled:true,bevelSize:.06,bevelThickness:.06,bevelSegments:3,curveSegments:24});gg.center();
 var gear=new THREE.Mesh(gg,new THREE.MeshStandardMaterial({color:INDIGO,metalness:.35,roughness:.38}));
 var tex=new THREE.TextureLoader().load("data:image/png;base64,@@LOGO@@");
 var disc=new THREE.Mesh(new THREE.CircleGeometry(1.02,64),new THREE.MeshBasicMaterial({map:tex}));disc.position.z=.1;
 var back=disc.clone();back.rotation.y=Math.PI;back.position.z=-.1;
 var gearG=new THREE.Group();gearG.add(gear,disc,back);scene.add(gearG);
 // layer slabs
 var slabs=[],slabG=new THREE.Group();
 for(var i=0;i<4;i++){
  var m=new THREE.MeshStandardMaterial({color:i>=1?RED.clone():INDIGO.clone(),metalness:.2,roughness:.45,transparent:true,opacity:.96});
  var b=new THREE.Mesh(new THREE.BoxGeometry(3.4,.28,2.3),m);
  var e=new THREE.LineSegments(new THREE.EdgesGeometry(b.geometry),new THREE.LineBasicMaterial({color:0xffffff,transparent:true,opacity:.55}));
  b.add(e);slabG.add(b);slabs.push(b)}
 scene.add(slabG);
 var S={spin:0,gearOn:1,gx:1,slabOn:0,explode:0,mod:0,rx:.55,ry:0,sx:-1,cam:9,pan:0};
 var tl=gsap.timeline({defaults:{ease:"none"},scrollTrigger:{trigger:"#story",start:"top top",end:"bottom bottom",scrub:.9}});
 tl.to(S,{spin:Math.PI*2,cam:7.6,duration:1},0)
   .to(S,{gearOn:0,gx:-1.4,duration:.6},.9)
   .to(S,{slabOn:1,duration:.5},1.1).to(S,{explode:1,ry:.9,duration:1},1.1)
   .to(S,{sx:1,duration:1},2.1).to(S,{mod:1,ry:2.2,rx:.4,duration:1},2.1)
   .to(S,{explode:.15,duration:.8},3.1);
 var mx=0,my=0;addEventListener("pointermove",function(e){mx=e.clientX/innerWidth-.5;my=e.clientY/innerHeight-.5});
 function size(){var w=canvas.clientWidth,h=canvas.clientHeight;renderer.setSize(w,h,false);cam.aspect=w/h;cam.updateProjectionMatrix()}
 size();addEventListener("resize",size);
 var cur={spin:0,gx:1,sx:-1,ex:0,mod:0,ry:0,rx:.55,on:1,son:0,cam:9};
 function frame(){
  var asp=cam.aspect,wide=asp>1?1:0;
  for(var k in cur){var t={spin:S.spin,gx:S.gx,sx:S.sx,ex:S.explode,mod:S.mod,ry:S.ry,rx:S.rx,on:S.gearOn,son:S.slabOn,cam:S.cam}[k];cur[k]+=(t-cur[k])*.12}
  cam.position.set(mx*.6,-my*.4+.2,cur.cam*(wide?1:1.5));cam.lookAt(0,0,0);
  var gxPos=wide?cur.gx*2.5:0,gyPos=wide?0:1.9;
  gearG.position.set(gxPos,gyPos,0);gearG.rotation.set(my*.4,cur.spin+mx*.5,0);gearG.rotation.z=cur.spin*.35;
  var gs=Math.max(.001,cur.on)*(wide?1:.7);gearG.scale.setScalar(gs);gearG.visible=cur.on>.01;
  slabG.visible=cur.son>.01;slabG.position.set(wide?cur.sx*2.0:0,wide?0:2.0,0);slabG.rotation.set(cur.rx,cur.ry,0);slabG.scale.setScalar((wide?.8:.6)*cur.son);
  slabs.forEach(function(b,i){b.position.y=(i-1.5)*(.34+cur.ex*.85);
   var good=i===0?1:cur.mod>(i-1)/3+.05?1:0, f=Math.min(1,Math.max(0,(cur.mod*3.2-(i-1))));
   b.material.color.copy(i===0?INDIGO:RED).lerp(INDIGO,i===0?0:f)});
  renderer.render(scene,cam);requestAnimationFrame(frame)}
 frame();
 gsap.utils.toArray(".act .box,.row,.q,.price").forEach(function(e){gsap.from(e,{y:36,opacity:0,duration:.7,ease:"power2.out",scrollTrigger:{trigger:e,start:"top 88%",once:true}})});
};
