function BUILD(scene,cam){
 scene.fog=new THREE.Fog(0x0e0e1a,30,90);
 scene.add(new THREE.HemisphereLight(0xdfe2ff,0x1a1a33,1.05));
 var key=new THREE.DirectionalLight(0xffffff,1.3);key.position.set(8,18,10);scene.add(key);
 var rim=new THREE.DirectionalLight(0x7f7fff,1.0);rim.position.set(-10,6,-8);scene.add(rim);
 var N=2800,CELL=.3,R=rnd(7);
 // layouts: old (leaning, gaps, rusty) and new (clean, stepped, indigo)
 function layout(kind){
  var P=[],y=0,k=rnd(kind==="old"?11:23);
  while(P.length<N){
   var f=Math.min(1,y/55),w,d,ox=0,oz=0,rot=0,keep=1;
   if(kind==="old"){w=Math.round(9-f*4);d=Math.round(8-f*3);ox=Math.sin(y*.11)*.9+f*1.2;oz=Math.cos(y*.09)*.7;rot=Math.sin(y*.07)*.12;keep=.78-f*.1}
   else{var tier=y<22?0:y<38?1:2;w=[9,7,5][tier];d=[8,6,4][tier];keep=1}
   for(var i=0;i<w;i++)for(var j=0;j<d;j++){
    if(k()>keep)continue;
    var lx=(i-(w-1)/2)*CELL,lz=(j-(d-1)/2)*CELL;
    var c=Math.cos(rot),s=Math.sin(rot);
    P.push({x:lx*c-lz*s+ox,y:y*CELL+.15,z:lx*s+lz*c+oz,tilt:kind==="old"?(k()-.5)*.35:0,ty:kind==="old"?(k()-.5)*.5:0,i:P.length});
    if(P.length>=N)break;
   }
   y++;
  }
  return P;
 }
 var A=layout("old"),B=layout("new");
 var maxAy=0,maxBy=0;A.forEach(function(q){maxAy=Math.max(maxAy,q.y)});B.forEach(function(q){maxBy=Math.max(maxBy,q.y)});
 var geo=new THREE.BoxGeometry(CELL*.9,CELL*.9,CELL*.9);
 var mat=new THREE.MeshStandardMaterial({roughness:.5,metalness:.15});
 var mesh=new THREE.InstancedMesh(geo,mat,N);mesh.instanceMatrix.setUsage(THREE.DynamicDrawUsage);scene.add(mesh);
 mesh.frustumCulled=false;
 var cloud=[],delE=[],delR=[],cOld=[],cNew=[],spin=[];
 var rust=[0x8a3b2a,0xa5472f,0x6b2b22,0x7a6a60,0x59504c,0xb5651d],ind=[0x5454d4,0x6767e8,0x4343b8,0x8a8af5,0xdfe0ff,0x3a3aa0];
 for(var i=0;i<N;i++){
  var th=R()*Math.PI*2,ph=Math.acos(2*R()-1),rr=6+R()*9;
  cloud.push({x:Math.sin(ph)*Math.cos(th)*rr,y:7+Math.cos(ph)*rr*.6+4,z:Math.sin(ph)*Math.sin(th)*rr});
  delE.push((1-A[i].y/maxAy)*.55+R()*.35);
  delR.push((B[i].y/maxBy)*.6+R()*.3);
  cOld.push(new THREE.Color(rust[Math.floor(R()*rust.length)]));
  var q=R();cNew.push(new THREE.Color(q>.93?ind[4]:ind[Math.floor(R()*4)]));
  spin.push([R()*6-3,R()*6-3,R()*6-3]);
 }
 var dummy=new THREE.Object3D(),col=new THREE.Color();
 // ground
 var ground=new THREE.Mesh(new THREE.CircleGeometry(26,64),new THREE.MeshStandardMaterial({color:0x14142a,roughness:.9}));ground.rotation.x=-Math.PI/2;scene.add(ground);
 var grid=new THREE.GridHelper(52,52,0x2c2c55,0x1d1d3a);grid.position.y=.01;scene.add(grid);
 return{update:function(p,t,mx,my,asp){
  var e=sstep(.18,.46,p),rb=sstep(.46,.88,p),sway=Math.sin(t*.8)*.03*(1-e);
  for(var i=0;i<N;i++){
   var a=A[i],b=B[i],ce=cloud[i];
   var ei=Math.max(0,Math.min(1,(e*1.9-delE[i]*.9)/1.0)),ri=Math.max(0,Math.min(1,(rb*1.9-delR[i]*.9)/1.0));
   ei=ei*ei*(3-2*ei);ri=ri*ri*(3-2*ri);
   var ang=t*.25*(ei*(1-ri)),ca=Math.cos(ang),sa=Math.sin(ang);
   var cxp=ce.x*ca-ce.z*sa,czp=ce.x*sa+ce.z*ca,cyp=ce.y+Math.sin(t*.9+i)*.25;
   var x,y,z,rx,ry,rz;
   if(ri<=0){x=a.x+(cxp-a.x)*ei+sway*a.y;y=a.y+(cyp-a.y)*ei;z=a.z+(czp-a.z)*ei;rx=a.tilt*(1-ei)+spin[i][0]*ei*t*.2;ry=a.ty*(1-ei)+spin[i][1]*ei*t*.2;rz=spin[i][2]*ei*t*.2}
   else{x=cxp+(b.x-cxp)*ri;y=cyp+(b.y-cyp)*ri;z=czp+(b.z-czp)*ri;var k=1-ri;rx=spin[i][0]*k*t*.2;ry=spin[i][1]*k*t*.2;rz=spin[i][2]*k*t*.2}
   dummy.position.set(x,y,z);dummy.rotation.set(rx,ry,rz);dummy.updateMatrix();mesh.setMatrixAt(i,dummy.matrix);
   col.copy(cOld[i]).lerp(cNew[i],Math.max(ri,0)).lerp(cNew[i],0);mesh.setColorAt(i,col);
  }
  mesh.instanceMatrix.needsUpdate=true;mesh.instanceColor.needsUpdate=true;
  var ang2=.5+p*1.7+mx*.4,wide=asp>=1,rad=wide?36:54,h=10+p*4-my*2;
  var side=Math.cos(p*4*Math.PI-Math.PI*.5);
  cam.position.set(Math.sin(ang2)*rad,h,Math.cos(ang2)*rad);
  var lk=new THREE.Vector3(Math.cos(ang2)*(wide?side*11:0),wide?10:13,-Math.sin(ang2)*(wide?side*11:0));cam.lookAt(lk);
 }}
}
