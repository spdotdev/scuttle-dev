function BUILD(scene,cam){
 scene.fog=new THREE.Fog(0x0e0e1a,18,50);
 scene.add(new THREE.HemisphereLight(0xdfe2ff,0x1a1a33,1.0));
 var key=new THREE.DirectionalLight(0xffffff,1.3);key.position.set(4,9,8);scene.add(key);
 var rim=new THREE.DirectionalLight(0x7f7fff,1.1);rim.position.set(-6,4,-6);scene.add(rim);
 function M(c,r,m){return new THREE.MeshStandardMaterial({color:c,roughness:r==null?.5:r,metalness:m||0})}
 // floor with tick marks so walking reads as movement
 var floor=new THREE.Mesh(new THREE.BoxGeometry(80,.4,10),M(0x15152b,.85));floor.position.set(10,-.2,1);scene.add(floor);
 for(var i=-8;i<40;i++){var t=new THREE.Mesh(new THREE.BoxGeometry(.07,.02,10),M(0x2a2a52,.9));t.position.set(i*2,.01,1);scene.add(t)}
 // crab
 var crab=new THREE.Group();scene.add(crab);
 var shell=M(0x5454d4,.38,.15),shell2=M(0x7d7df2,.35,.1),dark=M(0x2c2c78,.55);
 var body=new THREE.Mesh(new THREE.SphereGeometry(1,40,28),shell);body.scale.set(1.6,.7,1.15);body.position.y=1.0;crab.add(body);
 var top=new THREE.Mesh(new THREE.SphereGeometry(1,32,20),shell2);top.scale.set(1.2,.42,.85);top.position.y=1.34;crab.add(top);
 [-1,1].forEach(function(s){
  var st=new THREE.Mesh(new THREE.CylinderGeometry(.05,.06,.45,10),dark);st.position.set(s*.55,1.62,.7);crab.add(st);
  var eye=new THREE.Mesh(new THREE.SphereGeometry(.18,20,16),M(0xffffff,.3));eye.position.set(s*.55,1.9,.72);crab.add(eye);
  var pu=new THREE.Mesh(new THREE.SphereGeometry(.09,16,12),M(0x0a0a14,.2));pu.position.set(s*.55,1.92,.88);crab.add(pu);
 });
 var legs=[];
 [-1,1].forEach(function(s){for(var i=0;i<3;i++){
  var up=new THREE.Group();up.position.set(s*1.4,.95,-.3+i*.38);
  var s1=new THREE.Mesh(new THREE.CylinderGeometry(.07,.09,.95,10),dark);s1.rotation.z=Math.PI/2;s1.position.x=s*.47;up.add(s1);
  var knee=new THREE.Group();knee.position.set(s*.95,0,0);knee.rotation.z=s*.3;up.add(knee);
  var s2=new THREE.Mesh(new THREE.CylinderGeometry(.045,.07,1.0,10),dark);s2.position.y=-.5;knee.add(s2);
  crab.add(up);legs.push({up:up,s:s,i:i});
 }});
 var arms=[];
 [-1,1].forEach(function(s){
  var arm=new THREE.Group();arm.position.set(s*1.25,1.1,.7);crab.add(arm);
  var a1=new THREE.Mesh(new THREE.CylinderGeometry(.09,.12,.95,12),shell2);a1.rotation.x=Math.PI/2;a1.position.z=.47;arm.add(a1);
  var base=new THREE.Mesh(new THREE.SphereGeometry(.34,24,18),shell);base.scale.set(1,.85,1.25);base.position.z=1.0;arm.add(base);
  var pv=[1,-1].map(function(d){var g=new THREE.Group();g.position.set(0,0,1.05);var cn=new THREE.Mesh(new THREE.ConeGeometry(.17,.75,16),shell2);cn.rotation.x=Math.PI/2;cn.position.z=.38;g.add(cn);arm.add(g);return {g:g,d:d}});
  arms.push({arm:arm,pv:pv,s:s});
 });
 // props
 function label(l1,l2,bg,fg){var cv=document.createElement("canvas");cv.width=cv.height=256;var x=cv.getContext("2d");x.fillStyle=bg;x.fillRect(0,0,256,256);x.strokeStyle=fg;x.lineWidth=8;x.strokeRect(10,10,236,236);x.fillStyle=fg;x.textAlign="center";x.font="bold 54px monospace";x.fillText(l1,128,118);x.font="bold 30px monospace";x.fillText(l2,128,170);return new THREE.CanvasTexture(cv)}
 var oldB=new THREE.Mesh(new THREE.BoxGeometry(1.5,1.5,1.5),M(0xffffff,.6));oldB.material.map=label("PHP 5.6","END OF LIFE","#7a1f18","#ffd9d4");
 var newB=new THREE.Mesh(new THREE.BoxGeometry(1.5,1.5,1.5),M(0xffffff,.45));newB.material.map=label("PHP 8.x","SUPPORTED","#2d2d8c","#e6e6ff");
 var blk=new THREE.Group();blk.position.set(3.5,.75,2.6);blk.add(oldB,newB);scene.add(blk);newB.scale.setScalar(.001);
 function gearShape(teeth,ro,ri,hole){var s=new THREE.Shape(),n=teeth*4,i,a,r;for(i=0;i<n;i++){a=i/n*Math.PI*2;r=(i%4<2)?ro:ri;if(i)s.lineTo(Math.cos(a)*r,Math.sin(a)*r);else s.moveTo(Math.cos(a)*r,Math.sin(a)*r)}s.closePath();var h=new THREE.Path();h.absarc(0,0,hole,0,Math.PI*2,true);s.holes.push(h);return s}
 var gg=new THREE.ExtrudeGeometry(gearShape(12,1.15,.95,.45),{depth:.35,bevelEnabled:true,bevelSize:.04,bevelThickness:.04,bevelSegments:2});gg.center();
 var gear=new THREE.Mesh(gg,M(0x9a9aff,.35,.4));gear.position.set(9,1.35,2.6);scene.add(gear);
 var lockG=new THREE.Group();lockG.position.set(14.5,0,2.6);scene.add(lockG);
 var lockB=new THREE.Mesh(new THREE.BoxGeometry(1.6,1.25,.7),M(0xd9a21b,.4,.3));lockB.position.y=.65;lockG.add(lockB);
 var sh=new THREE.Mesh(new THREE.TorusGeometry(.5,.11,16,40,Math.PI),M(0xcfd2e6,.3,.7));sh.position.set(0,1.28,0);lockG.add(sh);
 var key2=new THREE.Mesh(new THREE.CylinderGeometry(.11,.11,.1,16),M(0x0a0a14));key2.rotation.x=Math.PI/2;key2.position.set(0,.7,.37);lockG.add(key2);
 var okMat=M(0x3fbf7f,.4,.2);
 return{update:function(p,t,mx,my,asp){
  var cx=-4+20*p,ph=cx*2.4;
  crab.position.x=cx;crab.rotation.y=Math.sin(ph*.5)*.05+mx*.35;crab.position.y=Math.abs(Math.sin(ph))*.05;
  legs.forEach(function(L){var o=L.i*2.1+(L.s>0?0:Math.PI);L.up.rotation.z=L.s*Math.max(0,Math.sin(ph+o))*.38;L.up.rotation.y=Math.sin(ph+o+1.2)*.28*L.s})
  var near=Math.max(bump(p,.375,.11),bump(p,.65,.11),bump(p,.925,.07));
  var wave=Math.sin(t*2.2)*.15;
  arms.forEach(function(A){A.arm.rotation.x=-near*1.0-.15+wave*(1-near)*(A.s>0?1:-1);A.arm.rotation.y=A.s*(.25-near*.2);
   var open=.28+.22*Math.sin(t*3+(A.s>0?0:2))*(.6+near*.6);A.pv.forEach(function(q){q.g.rotation.x=-q.d*open})});
  // block: lifted out, replaced by the supported one
  var u=sstep(.36,.46,p);oldB.position.y=u*2.4;oldB.rotation.y=u*Math.PI*2;oldB.scale.setScalar(Math.max(.001,1-sstep(.42,.5,p)));
  newB.scale.setScalar(Math.max(.001,sstep(.43,.52,p)));newB.rotation.y=-(1-sstep(.43,.55,p))*Math.PI;
  // gear turns while the crab works on it
  gear.rotation.z=p*40;gear.position.y=1.35+Math.sin(t*1.5)*.03;
  // lock closes
  var k=sstep(.89,.95,p);sh.position.y=1.28+(1-k)*.55;sh.rotation.z=(1-k)*.5;lockB.material=k>.95?okMat:lockB.material;
  var side=Math.cos(p*4*Math.PI-Math.PI*.5),wide=asp>=1;
  var lx=wide?cx-side*3.4:cx,camZ=wide?11.5:17;
  cam.position.set(lx+1.6+mx*1.2,3.4-my*.5,camZ);cam.lookAt(lx,wide?1.2:-.6,1.2);
 }}
}
