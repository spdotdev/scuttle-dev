function BUILD(scene,cam){
 scene.fog=new THREE.FogExp2(0x0e0e1a,.03);
 scene.add(new THREE.HemisphereLight(0xdfe2ff,0x1a1a33,.9));
 var pl=new THREE.PointLight(0xffffff,1.4,40);scene.add(pl);
 var RED=new THREE.Color(0xff5a4a),IND=new THREE.Color(0x7a7aff);
 var T=220,zOf=function(p){return 10-T*p};
 // rings along the tunnel
 var ringGeo=new THREE.TorusGeometry(4.6,.04,8,64),rings=[];
 for(var i=0;i<42;i++){var m=new THREE.Mesh(ringGeo,new THREE.MeshBasicMaterial({color:0x5a5ae0,transparent:true,opacity:.55}));m.position.z=10-i*5.5;scene.add(m);rings.push(m)}
 // dust
 var DN=3200,pos=new Float32Array(DN*3),r=rnd(5);
 for(var i=0;i<DN;i++){var a=r()*Math.PI*2,rad=3+r()*10;pos[i*3]=Math.cos(a)*rad;pos[i*3+1]=Math.sin(a)*rad;pos[i*3+2]=20-r()*260}
 var pg=new THREE.BufferGeometry();pg.setAttribute("position",new THREE.BufferAttribute(pos,3));
 var dust=new THREE.Points(pg,new THREE.PointsMaterial({color:0xa9a9ff,size:.09,transparent:true,opacity:.75,sizeAttenuation:true}));scene.add(dust);
 // gates: a frame, a soft plane and a label, red until passed
 function label(l1,l2,fg){var cv=document.createElement("canvas");cv.width=1024;cv.height=420;var x=cv.getContext("2d");x.fillStyle="rgba(14,14,26,.78)";x.fillRect(0,0,1024,420);x.strokeStyle=fg;x.lineWidth=8;x.strokeRect(8,8,1008,404);x.fillStyle=fg;x.font="500 40px monospace";x.fillText(l1.toUpperCase(),50,100);x.font="bold 92px monospace";x.fillText(l2[0],50,225);x.font="500 46px monospace";x.fillText(l2[1],50,320);var t=new THREE.CanvasTexture(cv);return t}
 var defs=[{p:.22,l:"Operating system",o:["CentOS 7","support ended 30 Jun 2024"],n:["Supported OS","patched and maintained"]},
  {p:.42,l:"Database",o:["MySQL 5.7","support ended Oct 2023"],n:["Supported database","upgraded and tested"]},
  {p:.62,l:"Language",o:["PHP 7.4","support ended 28 Nov 2022"],n:["PHP 8.x","supported, typed, faster"]},
  {p:.82,l:"Application",o:["Old code paths","no tests, no owner"],n:["Tested code","documented and owned"]}];
 var gates=defs.map(function(d){
  var g=new THREE.Group();g.position.z=zOf(d.p);scene.add(g);
  var mat=new THREE.MeshBasicMaterial({color:RED.clone()});
  [[0,3.6,10.4,.45],[0,-3.6,10.4,.45],[-5.1,0,.45,7.6],[5.1,0,.45,7.6]].forEach(function(b){var m=new THREE.Mesh(new THREE.BoxGeometry(b[2],b[3],.25),mat);m.position.set(b[0],b[1],0);g.add(m)});
  var fill=new THREE.Mesh(new THREE.PlaneGeometry(10,7.2),new THREE.MeshBasicMaterial({color:RED.clone(),transparent:true,opacity:.2,side:THREE.DoubleSide}));g.add(fill);
  var tOld=label(d.l,d.o,"#ff9d92"),tNew=label(d.l,d.n,"#b9b9ff");
  var lab=new THREE.Mesh(new THREE.PlaneGeometry(7.4,3.0),new THREE.MeshBasicMaterial({map:tOld,transparent:true}));lab.position.set(0,5.4,0);g.add(lab);
  return{g:g,mat:mat,fill:fill,lab:lab,tOld:tOld,tNew:tNew,z:g.position.z,done:false};
 });
 // padlock at the end
 var lock=new THREE.Group();lock.position.set(0,0,zOf(1)-3);scene.add(lock);
 var lb=new THREE.Mesh(new THREE.BoxGeometry(4,3.2,1.4),new THREE.MeshStandardMaterial({color:0x5454d4,roughness:.35,metalness:.4}));lock.add(lb);
 var sh=new THREE.Mesh(new THREE.TorusGeometry(1.25,.28,16,48,Math.PI),new THREE.MeshStandardMaterial({color:0xd4d6ea,roughness:.25,metalness:.8}));sh.position.y=1.6;lock.add(sh);
 var kh=new THREE.Mesh(new THREE.CylinderGeometry(.3,.3,.2,20),new THREE.MeshBasicMaterial({color:0x0a0a14}));kh.rotation.x=Math.PI/2;kh.position.z=.72;lock.add(kh);
 return{update:function(p,t,mx,my){
  var z=zOf(p*.97);
  var x=Math.sin(p*11)*.7+mx*1.6,y=Math.cos(p*8)*.4-my*1.0;
  cam.position.set(x,y,z);cam.lookAt(x*.4,y*.4,z-12);cam.rotation.z+=Math.sin(p*9)*.04;
  pl.position.set(x,y+1,z-3);
  dust.rotation.z=t*.01;
  gates.forEach(function(G){
   var passed=z<G.z-1.2,k=sstep(G.z+6,G.z-6,z);
   G.mat.color.copy(RED).lerp(IND,k);G.fill.material.color.copy(RED).lerp(IND,k);
   if(passed!==G.done){G.done=passed;G.lab.material.map=passed?G.tNew:G.tOld;G.lab.material.needsUpdate=true}
  });
  var k2=sstep(.88,.97,p);sh.position.y=1.6+(1-k2)*1.1;sh.rotation.z=(1-k2)*.6;lock.rotation.y=Math.sin(t*.6)*.25;
  rings.forEach(function(m,i){m.material.color.setHSL(.66,.7,.55+Math.sin(t*1.5+i*.5)*.1)});
 }}
}
