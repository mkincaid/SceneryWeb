var AC=AC||{};var HG=HG||{};AC.SurfaceType={POLYGON:0,LINE_LOOP:1,LINE_STRIP:2};AC.SurfaceFlag={SHADED:16,TWO_SIDED:32};AC.File=function(a){var b=new AC.Stream(a);this.materials=[];this.objects=[];this.parse(b)};AC.File.prototype.parse=function(b){var a=mat4.identity();b.readToken();while(b.pending()){switch(b.readToken()){case"MATERIAL":this.materials.push(new AC.Material(b));break;case"OBJECT":this.objects.push(new AC.Object(b,a));break}}};AC.Material=function(a){this.name=a.readString();a.readToken();this.diffuse=a.readVector(3);a.readToken();this.ambient=a.readVector(3);a.readToken();this.emissive=a.readVector(3);a.readToken();this.specular=a.readVector(3);a.readToken();this.shininess=a.readFloat();a.readToken();this.transparency=a.readFloat()};AC.Object=function(c,a){var b;this.defaultValues();this.type=c.readString();while(undefined===b){switch(c.readToken()){case"name":this.name=c.readString();break;case"data":this.data=c.readBlob(c.readInteger());break;case"texture":this.texture=c.readString();break;case"texrep":this.textureRepeat=c.readVector(2);break;case"texoff":this.textureOffset=c.readVector(2);break;case"rot":this.rotation=c.readVector(9);break;case"loc":this.translation=c.readVector(3);break;case"crease":this.crease=c.readFloat();break;case"url":this.url=c.readString();break;case"numvert":this.vertices=this.parseVertices(c,a);break;case"numsurf":this.surfaces=this.parseSurfaces(c);break;case"kids":b=c.readInteger();if(0!==b){this.children=this.parseKids(c,b,a)}break}}if(this.surfaces){this.smoothNormals(this.sharedVertices())}};AC.Object.prototype.defaultValues=function(){this.textureOffset=[0,0];this.textureRepeat=[1,1];this.rotation=[1,0,0,0,1,0,0,0,1];this.translation=[0,0,0];this.crease=61};AC.Object.prototype.parseVertices=function(g,a){var c=[],e=vec3.create(),b=mat4.identity(),f=g.readInteger(),d=0;this.compose(b,this.rotation,this.translation);mat4.multiply(b,a);for(;d<f;++d){e[0]=g.readFloat();e[1]=g.readFloat();e[2]=g.readFloat();mat4.multiplyVec3(b,e);c.push(e[0],e[1],e[2])}return c};AC.Object.prototype.parseSurfaces=function(e){var b=[],d=e.readInteger(),c=0,a;for(;c<d;++c){a=new AC.Surface(e,this);if(!a.degenerated){b.push(a)}}return b};AC.Object.prototype.parseKids=function(f,c,a){var e=[],b=mat4.identity(),d=0;this.compose(b,this.rotation,this.translation);mat4.multiply(b,a);for(;d<c;++d){f.readToken();e.push(new AC.Object(f,b))}return e};AC.Object.prototype.sharedVertices=function(){var a=this.surfaces,f=a.length,c=[],e=0,d,l,h,g,b,k;for(;e<f;++e){b=a[e];l=b.indices;h=l.length;for(d=0;d<h;++d){g=l[d];k=c[g];if(!k){c[g]=[b]}else{if(k.indexOf(b)===-1){k.push(b)}}}}return c};AC.Object.prototype.smoothNormals=function(a){var f=this.surfaces,x=f.length,q=Math.cos(this.crease*Math.PI/180),o=0,n,m,p,c,b,d,l,h,u,g,w,e,t,s,r,v;for(;o<x;++o){l=f[o];l.normals=g=[];w=l.normal;c=l.indices;b=c.length;for(n=0;n<b;++n){d=c[n];t=w[0];s=w[1];r=w[2];h=a[d];p=h.length;for(m=0;m<p;++m){u=h[m];if(l!==u){e=u.normal;if(w[0]*e[0]+w[1]*e[1]+w[2]*e[2]>q*w[3]*e[3]){t+=e[0];s+=e[1];r+=e[2]}}}g.push(t,s,r)}}};AC.Object.prototype.compose=function(a,c,b){a[0]=c[0];a[1]=c[1];a[2]=c[2];a[4]=c[3];a[5]=c[4];a[6]=c[5];a[8]=c[6];a[9]=c[7];a[10]=c[8];a[12]=b[0];a[13]=b[1];a[14]=b[2]};AC.Surface=function(c,b){var a;while(undefined===a){switch(c.readToken()){case"SURF":this.parseFlags(c);break;case"mat":this.materialId=c.readInteger();break;case"refs":a=c.readInteger();this.parseRefs(c,b,a);break}}this.normal=[0,0,0,1];if(this.type===AC.SurfaceType.POLYGON){if(!this.teselate(b,a)){this.degenerated=true}}};AC.Surface.prototype.parseFlags=function(b){var a=b.readInteger();this.type=a&15;this.isShaded=(a&AC.SurfaceFlag.SHADED)!==0;this.isTwoSided=(a&AC.SurfaceFlag.TWO_SIDED)!==0};AC.Surface.prototype.parseRefs=function(j,a,g){var e=a.textureOffset[0],d=a.textureOffset[1],h=a.textureRepeat[0],f=a.textureRepeat[1],k=[],b=[],c=0;for(;c<g;++c){k.push(j.readInteger());b.push(e+j.readFloat()*h);b.push(d+j.readFloat()*f)}this.indices=k;this.uvs=b};AC.Surface.prototype.teselate=function(c,b){var a=false;
if(b>=3){a=this.calculateNormal(c);if(a){if(b>3){a=this.triangulate(c)}}}return a};AC.Surface.prototype.calculateNormal=function(g){var o=g.vertices,k=this.indices,f=k[0]*3,d=k[1]*3,b=k[2]*3,h=o[d]-o[f],e=o[d+1]-o[f+1],c=o[d+2]-o[f+2],a=o[b]-o[f],q=o[b+1]-o[f+1],p=o[b+2]-o[f+2],m=e*p-q*c,l=c*a-p*h,j=h*q-a*e,n=Math.sqrt(m*m+l*l+j*j);this.normal=[m,l,j,n];return n>1e-10};AC.Surface.prototype.triangulate=function(b){var g=b.vertices,l=this.indices,a=this.normal,k=0,j=1,m=[],f=l.length,d=0,e,c,h;h=Math.max(Math.abs(a[0]),Math.abs(a[1]),Math.abs(a[2]));if(h===Math.abs(a[0])){k=1;j=2}else{if(h===Math.abs(a[1])){k=0;j=2}}for(;d<f;++d){e=l[d]*3;m.push({x:g[e+k],y:g[e+j]})}c=AC.Triangulator.triangulate(m);if(c){this.sortRefs(c)}return null!==c};AC.Surface.prototype.sortRefs=function(g){var h=this.indices,f=this.uvs,d=[],b=[],a=g.length,e=0,c;for(;e<a;++e){c=g[e];d.push(h[c]);b.push(f[c*2],f[c*2+1])}this.indices=d;this.uvs=b};AC.Stream=function(a){this.buffer=a;this.position=0};AC.Stream.prototype.pending=function(){return this.position!==this.buffer.length};AC.Stream.prototype.space=function(){var a=this.buffer[this.position];return(" "===a)||("\r"===a)||("\n"===a)};AC.Stream.prototype.quote=function(){return'"'===this.buffer[this.position]};AC.Stream.prototype.readToken=function(){var b=this.position,a;for(;this.pending()&&!this.space();++this.position){}a=this.buffer.substring(b,this.position);for(;this.pending()&&this.space();++this.position){}return a};AC.Stream.prototype.readString=function(){var c=this.quote(),b=c?this.quote:this.space,d=this.position,a;this.position+=c;for(;this.pending()&&!b.call(this);++this.position){}a=this.buffer.substring(d+c,this.position);this.position+=c;for(;this.pending()&&this.space();++this.position){}return a};AC.Stream.prototype.readBlob=function(a){var b=this.buffer.substr(this.position,a);this.position+=a;for(;this.pending()&&this.space();++this.position){}return b};AC.Stream.prototype.readInteger=function(){return parseInt(this.readToken())};AC.Stream.prototype.readFloat=function(){return parseFloat(this.readToken())};AC.Stream.prototype.readVector=function(a){var b=[],c=0;for(;c<a;++c){b.push(this.readFloat())}return b};HG.Camera=function(a){this.eye=vec3.create(a.eye);this.poi=vec3.create(a.poi);this.up=vec3.create(a.up);this.fov=a.fov;this.computeMatrix()};HG.Camera.prototype.computeMatrix=function(){this.transformer=mat4.lookAt(this.eye,this.poi,this.up)};HG.Camera.prototype.zoom=function(a){vec3.subtract(this.eye,this.poi);vec3.scale(this.eye,a);vec3.add(this.eye,this.poi);this.computeMatrix()};HG.Camera.prototype.rotate=function(c,a){var b=this.quaternion(c,a);vec3.subtract(this.eye,this.poi);quat4.multiplyVec3(b,this.eye);quat4.multiplyVec3(b,this.up);vec3.add(this.eye,this.poi);this.computeMatrix()};HG.Camera.prototype.localAxis=function(){var b=[],a=vec3.create();vec3.subtract(this.eye,this.poi,a);b[2]=vec3.normalize(vec3.create(a));b[1]=vec3.normalize(vec3.create(this.up));b[0]=vec3.normalize(vec3.cross(this.up,a,a));return b};HG.Camera.prototype.quaternion=function(e,c){var d=quat4.create(),b=e/2,a=Math.sin(b);d[0]=c[0]*a;d[1]=c[1]*a;d[2]=c[2]*a;d[3]=Math.cos(b);return d};HG.Loader={};HG.Loader.loadText=function(b,a,e,d){var c=new XMLHttpRequest();c.open("GET",b,true);c.overrideMimeType("text/plain; charset=x-user-defined");c.onload=function(){a[e](this.responseText,d)};c.send()};HG.Loader.loadBinary=function(b,a,e,d){var c=new XMLHttpRequest();c.open("GET",b,true);c.responseType="arraybuffer";c.onload=function(){a[e](this.response,d)};c.send()};HG.Renderer=function(a){this.gl=a.getContext("experimental-webgl",{alpha:true})||a.getContext("webgl",{alpha:true})};HG.Renderer.BackgroundColor=[0.5,0.5,0.65,1];HG.Renderer.LightPosition=[-50,50,50];HG.Renderer.LightAmbient=[0.2,0.2,0.2];HG.Renderer.prototype.setScene=function(c,b,a){this.scene=b;this.camera=a;this.reset();this.programs=this.programs||this.createPrograms();this.textures=this.createTextures(c,b.textures);this.buffers=this.createBuffers(b.groups)};HG.Renderer.prototype.reset=function(){var b=this.gl,a=HG.Renderer.BackgroundColor;b.viewport(0,0,b.canvas.width,b.canvas.height);b.clearColor(a[0],a[1],a[2],a[3]);b.enable(b.DEPTH_TEST);b.depthFunc(b.LEQUAL);b.depthMask(true);b.enable(b.CULL_FACE);b.frontFace(b.CCW);b.cullFace(b.BACK);b.disable(b.BLEND);b.blendEquation(b.FUNC_ADD);b.blendFunc(b.SRC_ALPHA,b.ONE_MINUS_SRC_ALPHA);this.depthMask=true;this.culling=true;this.blending=false;this.program=undefined};HG.Renderer.prototype.resize=function(b,a){var c=this.gl;c.canvas.width=b;c.canvas.height=a;c.viewport(0,0,b,a)};HG.Renderer.prototype.setDepthMask=function(a){var b=this.gl;if(this.depthMask!==a){b.depthMask(a)}this.depthMask=a};HG.Renderer.prototype.setCulling=function(b){var a=this.gl;if(this.culling!==b){if(b){a.enable(a.CULL_FACE)}else{a.disable(a.CULL_FACE)}}this.culling=b};HG.Renderer.prototype.setBlending=function(a){var b=this.gl;if(this.blending!==a){if(a){b.enable(b.BLEND)}else{b.disable(b.BLEND)}}this.blending=a};HG.Renderer.prototype.setProgram=function(a){var b=this.gl;if(this.program!==a){a.use(b)}this.program=a};HG.Renderer.prototype.render=function(){var a=this.gl;a.clear(a.COLOR_BUFFER_BIT|a.DEPTH_BUFFER_BIT);this.draw(a,this.scene,false);this.draw(a,this.scene,true)};HG.Renderer.prototype.draw=function(e,f,l){var a=f.groups,m=this.buffers,j=a.length,d=0,h,b,k,g,c;h=mat4.perspective(this.camera.fov,e.drawingBufferWidth/e.drawingBufferHeight,0.1,2000);b=mat4.toInverseMat3(this.camera.transformer);mat3.transpose(b);this.setBlending(l);this.setDepthMask(!l);for(d=0;d<j;++d){k=a[d];g=f.materials[k.materialId];if(l!==(0===g.transparency)){this.setCulling(!k.isTwoSided);c=this.getProgram(k);this.setProgram(c);e.bindBuffer(e.ARRAY_BUFFER,m[d]);e.uniformMatrix4fv(c.uniforms.uProjector,false,h);e.uniformMatrix4fv(c.uniforms.uTransformer,false,this.camera.transformer);e.vertexAttribPointer(c.attributes.aPosition,3,e.FLOAT,false,32,0);if(k.type!==AC.SurfaceType.POLYGON){e.uniform4fv(c.uniforms.uColor,f.materials[k.materialId].diffuse.concat(1))}if(k.type===AC.SurfaceType.POLYGON){e.uniformMatrix3fv(c.uniforms.uNormalizer,false,b);e.uniform3fv(c.uniforms.uEmissive,g.emissive);e.uniform3fv(c.uniforms.uAmbient,g.ambient);e.uniform3fv(c.uniforms.uDiffuse,g.diffuse);e.uniform3fv(c.uniforms.uSpecular,g.specular);e.uniform1f(c.uniforms.uShininess,g.shininess);e.uniform1f(c.uniforms.uTransparency,g.transparency);e.uniform3fv(c.uniforms.uLightPosition,HG.Renderer.LightPosition);e.uniform3fv(c.uniforms.uLightAmbient,HG.Renderer.LightAmbient);e.vertexAttribPointer(c.attributes.aNormal,3,e.FLOAT,false,32,20)}if(undefined!==k.textureId){e.activeTexture(e.TEXTURE0);e.bindTexture(e.TEXTURE_2D,this.textures[k.textureId]);e.uniform1i(c.uniforms.uSampler,0);e.vertexAttribPointer(c.attributes.aTexcoord,2,e.FLOAT,false,32,12)}e.drawArrays(this.getDrawMode(k.type),0,k.buffer.length/8);e.bindTexture(e.TEXTURE_2D,null);e.bindBuffer(e.ARRAY_BUFFER,null)}}};HG.Renderer.prototype.getProgram=function(b){var a;if(b.type===AC.SurfaceType.POLYGON){if(b.textureId===undefined){a=this.programs.phong}else{a=this.programs.phongTexture}}else{if(b.textureId===undefined){a=this.programs.color}else{a=this.programs.texture}}return a};HG.Renderer.prototype.getDrawMode=function(a){var c=this.gl,b;switch(a){case AC.SurfaceType.POLYGON:b=c.TRIANGLES;break;case AC.SurfaceType.LINE_STRIP:b=c.LINE_STRIP;break;case AC.SurfaceType.LINE_LOOP:b=c.LINE_LOOP;break}return b};HG.Renderer.prototype.createPrograms=function(){var a=this.gl;return{color:new HG.Shader(a,HG.Shader.Color),texture:new HG.Shader(a,HG.Shader.Texture),phong:new HG.Shader(a,HG.Shader.Phong),phongTexture:new HG.Shader(a,HG.Shader.PhongTexture)}
};HG.Renderer.prototype.createBuffers=function(a){var f=this.gl,d=[],c=a.length,e=0,b;for(;e<c;++e){b=f.createBuffer();d.push(b);f.bindBuffer(f.ARRAY_BUFFER,b);f.bufferData(f.ARRAY_BUFFER,new Float32Array(a[e].buffer),f.STATIC_DRAW);f.bindBuffer(f.ARRAY_BUFFER,null)}return d};HG.Renderer.prototype.createTextures=function(g,f){var h=this.gl,b=[],a=f.length,d=0,c,e;for(;d<a;++d){c=g+f[d];e=h.createTexture();b.push(e);switch(this.getExtension(c)){case"sgi":case"rgba":case"rgb":case"ra":case"bw":HG.Loader.loadBinary(c,this,"onSgiTextureLoaded",{texture:e});break;default:this.loadTexture(c,e);break}}return b};HG.Renderer.prototype.getExtension=function(b){var c="",a;a=b.lastIndexOf(".");if(-1!==a){c=b.substring(a+1)}return c.toLowerCase()};HG.Renderer.prototype.onSgiTextureLoaded=function(c,f){var e=this.gl,b=new SGI.File(c),d=this.isImagePowerOfTwo(b.img),a=d?e.REPEAT:e.CLAMP_TO_EDGE,g=d?e.LINEAR_MIPMAP_LINEAR:e.LINEAR;e.bindTexture(e.TEXTURE_2D,f.texture);e.pixelStorei(e.UNPACK_FLIP_Y_WEBGL,true);e.pixelStorei(e.UNPACK_PREMULTIPLY_ALPHA_WEBGL,false);e.texParameteri(e.TEXTURE_2D,e.TEXTURE_MIN_FILTER,g);e.texParameteri(e.TEXTURE_2D,e.TEXTURE_MAG_FILTER,g);e.texParameteri(e.TEXTURE_2D,e.TEXTURE_WRAP_S,a);e.texParameteri(e.TEXTURE_2D,e.TEXTURE_WRAP_T,a);e.texImage2D(e.TEXTURE_2D,0,e.RGBA,b.img.width,b.img.height,0,e.RGBA,e.UNSIGNED_BYTE,b.img.data);if(g===e.LINEAR_MIPMAP_LINEAR){e.generateMipmap(e.TEXTURE_2D)}e.bindTexture(e.TEXTURE_2D,null)};HG.Renderer.prototype.loadTexture=function(a,d){var g=this.gl,c=this,e=new Image(),f,b,h;e.onload=function(){f=c.isImagePowerOfTwo(e);b=f?g.REPEAT:g.CLAMP_TO_EDGE;h=f?g.LINEAR_MIPMAP_LINEAR:g.LINEAR;g.bindTexture(g.TEXTURE_2D,d);g.pixelStorei(g.UNPACK_FLIP_Y_WEBGL,true);g.pixelStorei(g.UNPACK_PREMULTIPLY_ALPHA_WEBGL,false);g.texParameteri(g.TEXTURE_2D,g.TEXTURE_MIN_FILTER,h);g.texParameteri(g.TEXTURE_2D,g.TEXTURE_MAG_FILTER,h);g.texParameteri(g.TEXTURE_2D,g.TEXTURE_WRAP_S,b);g.texParameteri(g.TEXTURE_2D,g.TEXTURE_WRAP_T,b);g.texImage2D(g.TEXTURE_2D,0,g.RGBA,g.RGBA,g.UNSIGNED_BYTE,e);if(h===g.LINEAR_MIPMAP_LINEAR){g.generateMipmap(g.TEXTURE_2D)}g.bindTexture(g.TEXTURE_2D,null)};e.src=a};HG.Renderer.prototype.isImagePowerOfTwo=function(a){return this.isPowerOfTwo(a.width)&&this.isPowerOfTwo(a.height)};HG.Renderer.prototype.isPowerOfTwo=function(a){return 0===(a&(a-1))};HG.Scene=function(a){this.materials=a.materials;this.textures=[];this.groups=[];this.boundingBox=new HG.BoundingBox();this.build(a.objects)};HG.Scene.prototype.build=function(a){this.buildGroups(a);this.groups.sort(HG.RenderGroup.sort)};HG.Scene.prototype.buildGroups=function(d){var a=d.length,c=0,b;for(;c<a;++c){b=d[c];if("light"!==b.type&&b.surfaces){this.buildGroup(b)}if(b.children){this.buildGroups(b.children)}}};HG.Scene.prototype.buildGroup=function(C){var p=C.texture,d=C.vertices,o=C.surfaces,E=o.length,c=this.boundingBox,w=0,v,u,s,m,h,g,b,n,q,B,A,a,f,r,t,e,D;if(p){D=this.getTextureId(p)}for(;w<E;++w){r=o[w];b=r.indices;n=r.uvs;q=r.normals;B=r.normal;A=r.isShaded;f=this.getGroup(r,D);t=f.buffer;a=b.length;for(v=u=s=0;v<a;++v,u+=2,s+=3){e=b[v]*3;m=d[e];h=d[e+1];g=d[e+2];t.push(m,h,g);t.push(n[u],n[u+1]);if(A){t.push(q[s],q[s+1],q[s+2])}else{t.push(B[0],B[1],B[2])}c.xmin=Math.min(c.xmin,m);c.xmax=Math.max(c.xmax,m);c.ymin=Math.min(c.ymin,h);c.ymax=Math.max(c.ymax,h);c.zmin=Math.min(c.zmin,g);c.zmax=Math.max(c.zmax,g)}}};HG.Scene.prototype.getGroup=function(a,c){var b=this.findGroup(a,c);if(!b){b=new HG.RenderGroup(a,c);this.groups.push(b)}return b};HG.Scene.prototype.findGroup=function(a,d){var b=this.groups.length-1,c;for(;b>=0;--b){c=this.groups[b];if(c.materialId===a.materialId&&c.textureId===d&&c.isTwoSided===a.isTwoSided&&c.type===AC.SurfaceType.POLYGON&&a.type===AC.SurfaceType.POLYGON){return c}}return undefined};HG.Scene.prototype.getTextureId=function(c){var b=this.textures,a=b.length,d=0;for(;d<a;++d){if(b[d]===c){break}}if(d===a){b.push(c)}return d};HG.RenderGroup=function(a,b){this.materialId=a.materialId;this.textureId=b;this.isTwoSided=a.isTwoSided;this.type=a.type;this.buffer=[]};HG.RenderGroup.sort=function(d,c){var g=d.textureId===undefined?-1:d.textureId,f=c.textureId===undefined?-1:c.textureId,e=d.type-c.type;if(0===e){e=g-f;if(0===e){e=d.materialId-c.materialId;if(0===e){e=d.isTwoSided-c.isTwoSided}}}return e};HG.BoundingBox=function(){this.xmin=Infinity;this.xmax=-Infinity;this.ymin=Infinity;this.ymax=-Infinity;this.zmin=Infinity;this.zmax=-Infinity};var SGI=SGI||{};SGI.Storage={VERBATIM:0,RLE:1};SGI.File=function(a){var b=new SGI.Stream(a);this.header=new SGI.Header(b);this.img=new SGI.Image(this.header);this.parse(b)};SGI.File.prototype.parse=function(a){switch(this.header.storage){case SGI.Storage.VERBATIM:this.verbatim(a);break;case SGI.Storage.RLE:this.rle(a);break}this.adjustChannels()};SGI.File.prototype.verbatim=function(i){var d=this.img.data,e=this.header.zsize,j=this.header.ysize,b=this.header.xsize,h=b*8,f=0,a=512,k,c,g;for(;f<e;++f){g=this.startChannel(f);for(k=0;k<j;++k,g-=h){for(c=0;c<b;++c,g+=4){d[g]=i.peekByte(a++)}}}};SGI.File.prototype.rle=function(h){var c=this.img.data,d=this.header.zsize,i=this.header.ysize,g=this.header.xsize*4,e=0,b=512,j,a,f;for(;e<d;++e){f=this.startChannel(e);for(j=0;j<i;++j,f-=g,b+=4){a=h.peekLong(b);this.rleRow(h,a,c,f)}}};SGI.File.prototype.rleRow=function(f,e,a,g){var d=f.peekByte(e++),c=d&127,b;while(0!==c){if(d&128){for(b=0;b<c;++b,g+=4){a[g]=f.peekByte(e++)}}else{d=f.peekByte(e++);for(b=0;b<c;++b,g+=4){a[g]=d}}d=f.peekByte(e++);c=d&127}};SGI.File.prototype.adjustChannels=function(){var b=this.img.data,c=b.length,a=this.header.zsize,d=0;if(4!==a){for(;d<c;d+=4){switch(a){case 1:b[d+1]=b[d+2]=b[d];b[d+3]=255;break;case 2:b[d+1]=b[d+2]=b[d];break;case 3:b[d+3]=255;break}}}};SGI.File.prototype.startChannel=function(b){var a=((this.header.ysize-1)*this.header.xsize*4);if((2===this.header.zsize)&&(1===b)){a+=2}return a+b};SGI.Header=function(a){this.storage=a.peekByte(2);this.xsize=a.peekShort(6);this.ysize=a.peekShort(8);this.zsize=a.peekShort(10)};SGI.Image=function(a){this.width=a.xsize;this.height=a.ysize;this.data=new Uint8Array(a.xsize*a.ysize*4)};SGI.Stream=function(a){this.buffer=new Uint8Array(a)};SGI.Stream.prototype.peekByte=function(a){return this.buffer[a]};SGI.Stream.prototype.peekShort=function(a){return(this.peekByte(a)<<8)|this.peekByte(a+1)};SGI.Stream.prototype.peekLong=function(a){return(this.peekByte(a)<<24)|(this.peekByte(a+1)<<16)|(this.peekByte(a+2)<<8)|this.peekByte(a+3)};HG.Shader=function(b,a){this.program=this.createProgram(b,a);this.attributes=a.attributes(b,this.program);this.uniforms=a.uniforms(b,this.program)};HG.Shader.prototype.createProgram=function(e,b){var a=e.createProgram(),d,c;c=b.defines+b.vs;d=this.createShader(e,e.VERTEX_SHADER,c);e.attachShader(a,d);c=b.defines+b.fs;d=this.createShader(e,e.FRAGMENT_SHADER,c);e.attachShader(a,d);e.linkProgram(a);return a};HG.Shader.prototype.createShader=function(d,a,c){var b=d.createShader(a);d.shaderSource(b,c);d.compileShader(b);return b};HG.Shader.prototype.use=function(b){b.useProgram(this.program);for(var a in this.attributes){b.enableVertexAttribArray(this.attributes[a])}};HG.Shader.Color={};HG.Shader.Color.vs="attribute vec3 aPosition;\n\n#ifdef TEXTURE\nattribute vec2 aTexcoord;\n#endif\n\nuniform mat4 uProjector;\nuniform mat4 uTransformer;\n\n#ifdef TEXTURE\nvarying vec2 vTexcoord;\n#endif\n\nvoid main(){\n#ifdef TEXTURE\n  vTexcoord = aTexcoord;\n#endif\n\n  gl_Position = uProjector * uTransformer * vec4(aPosition, 1.0);\n}";HG.Shader.Color.fs="#ifdef GL_ES\n  precision highp float;\n#endif\n\n#ifdef TEXTURE\nvarying vec2 vTexcoord;\n#endif\n\nuniform vec4 uColor;\n\n#ifdef TEXTURE\nuniform sampler2D uSampler;\n#endif\n\nvoid main(){\n#ifdef TEXTURE\n  gl_FragColor = texture2D(uSampler, vTexcoord) * uColor;\n#else\n  gl_FragColor = uColor;\n#endif\n}";HG.Shader.Color.defines="";HG.Shader.Color.attributes=function(b,a){return{aPosition:b.getAttribLocation(a,"aPosition")}};HG.Shader.Color.uniforms=function(b,a){return{uProjector:b.getUniformLocation(a,"uProjector"),uTransformer:b.getUniformLocation(a,"uTransformer"),uColor:b.getUniformLocation(a,"uColor")}};HG.Shader.Texture={};HG.Shader.Texture.fs=HG.Shader.Color.fs;HG.Shader.Texture.vs=HG.Shader.Color.vs;HG.Shader.Texture.defines="#define TEXTURE\n";HG.Shader.Texture.attributes=function(b,a){return{aPosition:b.getAttribLocation(a,"aPosition"),aTexcoord:b.getAttribLocation(a,"aTexcoord")}};HG.Shader.Texture.uniforms=function(b,a){return{uProjector:b.getUniformLocation(a,"uProjector"),uTransformer:b.getUniformLocation(a,"uTransformer"),uColor:b.getUniformLocation(a,"uColor"),uSampler:b.getUniformLocation(a,"uSampler")}};HG.Shader.Phong={};HG.Shader.Phong.vs="attribute vec3 aPosition;\nattribute vec3 aNormal;\n\n#ifdef TEXTURE\nattribute vec2 aTexcoord;\n#endif\n\nuniform mat4 uProjector;\nuniform mat4 uTransformer;\nuniform mat3 uNormalizer;\n\nvarying vec3 vPosition;\nvarying vec3 vNormal;\n\n#ifdef TEXTURE\nvarying vec2 vTexcoord;\n#endif\n\nvoid main(){\n  vPosition = (uTransformer * vec4(aPosition, 1.0) ).xyz;\n  vNormal = normalize(uNormalizer * aNormal);\n\n#ifdef TEXTURE\n  vTexcoord = aTexcoord;\n#endif\n\n  gl_Position = uProjector * uTransformer * vec4(aPosition, 1.0);\n}";HG.Shader.Phong.fs="#ifdef GL_ES\n  precision highp float;\n#endif\n\nvarying vec3 vPosition;\nvarying vec3 vNormal;\n\n#ifdef TEXTURE\nvarying vec2 vTexcoord;\n#endif\n\nuniform vec3 uEmissive;\nuniform vec3 uAmbient;\nuniform vec3 uDiffuse;\nuniform vec3 uSpecular;\nuniform float uShininess;\nuniform float uTransparency;\n\nuniform vec3 uLightPosition;\nuniform vec3 uLightAmbient;\n\n#ifdef TEXTURE\nuniform sampler2D uSampler;\n#endif\n\nvoid main(){\n  vec3 L = normalize(uLightPosition - vPosition);\n  vec3 E = normalize(-vPosition);\n  vec3 R = normalize( -reflect(L, vNormal) );\n\n#ifdef TEXTURE\n  vec4 sample = texture2D(uSampler, vTexcoord);\n\n  vec3 color = sample.rgb * \n    (uEmissive + \n     uAmbient * uLightAmbient + \n     uDiffuse * max( dot(vNormal, L), 0.0) ) + \n    uSpecular * 0.3 * pow( max( dot(R, E), 0.0), uShininess);\n\n  gl_FragColor = vec4(color, sample.a * (1.0 - uTransparency) );\n#else\n  vec3 color = uEmissive + \n    uAmbient * uLightAmbient + \n    uDiffuse * max( dot(vNormal, L), 0.0) + \n    uSpecular * 0.3 * pow( max( dot(R, E), 0.0), uShininess);\n\n  gl_FragColor = vec4(color, 1.0 - uTransparency);\n#endif\n}";HG.Shader.Phong.defines="";HG.Shader.Phong.attributes=function(b,a){return{aPosition:b.getAttribLocation(a,"aPosition"),aNormal:b.getAttribLocation(a,"aNormal")}
};HG.Shader.Phong.uniforms=function(b,a){return{uProjector:b.getUniformLocation(a,"uProjector"),uTransformer:b.getUniformLocation(a,"uTransformer"),uNormalizer:b.getUniformLocation(a,"uNormalizer"),uEmissive:b.getUniformLocation(a,"uEmissive"),uAmbient:b.getUniformLocation(a,"uAmbient"),uDiffuse:b.getUniformLocation(a,"uDiffuse"),uSpecular:b.getUniformLocation(a,"uSpecular"),uShininess:b.getUniformLocation(a,"uShininess"),uTransparency:b.getUniformLocation(a,"uTransparency"),uLightPosition:b.getUniformLocation(a,"uLightPosition"),uLightAmbient:b.getUniformLocation(a,"uLightAmbient")}};HG.Shader.PhongTexture={};HG.Shader.PhongTexture.fs=HG.Shader.Phong.fs;HG.Shader.PhongTexture.vs=HG.Shader.Phong.vs;HG.Shader.PhongTexture.defines="#define TEXTURE\n";HG.Shader.PhongTexture.attributes=function(b,a){return{aPosition:b.getAttribLocation(a,"aPosition"),aNormal:b.getAttribLocation(a,"aNormal"),aTexcoord:b.getAttribLocation(a,"aTexcoord")}};HG.Shader.PhongTexture.uniforms=function(b,a){return{uProjector:b.getUniformLocation(a,"uProjector"),uTransformer:b.getUniformLocation(a,"uTransformer"),uNormalizer:b.getUniformLocation(a,"uNormalizer"),uEmissive:b.getUniformLocation(a,"uEmissive"),uAmbient:b.getUniformLocation(a,"uAmbient"),uDiffuse:b.getUniformLocation(a,"uDiffuse"),uSpecular:b.getUniformLocation(a,"uSpecular"),uShininess:b.getUniformLocation(a,"uShininess"),uTransparency:b.getUniformLocation(a,"uTransparency"),uLightPosition:b.getUniformLocation(a,"uLightPosition"),uLightAmbient:b.getUniformLocation(a,"uLightAmbient"),uSampler:b.getUniformLocation(a,"uSampler")}};HG.Trackball=function(a,b){this.canvas=a;this.camera=b;this.x=0;this.y=0;this.down=false;this.addListeners(a)};HG.Trackball.prototype.addListeners=function(b){var c=this,d=function(f){c.onMouseDown(f)},e=function(f){c.onMouseWheel(f)},a=function(f){c.onMouseWheel(f)};b.addEventListener("mousedown",d,false);b.addEventListener("mousewheel",e,false);b.addEventListener("DOMMouseScroll",a,false)};HG.Trackball.prototype.onMouseDown=function(b){var a=this;this.down=true;this.x=b.clientX-this.canvas.offsetLeft;this.y=b.clientY-this.canvas.offsetTop;this.mu=function(c){a.onMouseUp(c)};this.mm=function(c){a.onMouseMove(c)};document.addEventListener("mouseup",this.mu,false);document.addEventListener("mousemove",this.mm,false);b.preventDefault()};HG.Trackball.prototype.onMouseUp=function(a){this.down=false;document.removeEventListener("mouseup",this.mu,false);document.removeEventListener("mousemove",this.mm,false);a.preventDefault()};HG.Trackball.prototype.onMouseMove=function(b){var a,c;if(this.down){a=b.clientX-this.canvas.offsetLeft;c=b.clientY-this.canvas.offsetTop;if(a!==this.x||c!==this.y){this.track(this.x,this.y,a,c);this.x=a;this.y=c}}};HG.Trackball.prototype.onMouseWheel=function(b){var a=b.wheelDelta?b.wheelDelta/120:-b.detail;this.camera.zoom(Math.max(0.05,1-a*0.05));b.preventDefault();return false};HG.Trackball.prototype.track=function(b,e,a,c){var h=this.project(b,e),f=this.project(a,c),g=Math.acos(vec3.dot(h,f)),d=vec3.create();if(g){vec3.cross(h,f,d);vec3.normalize(d);this.camera.rotate(-g,d)}};HG.Trackball.prototype.project=function(a,e){var b=this.camera.localAxis(),d=this.projectBall(a,e),c=vec3.create();vec3.scale(b[0],d[0]);vec3.scale(b[1],d[1]);vec3.scale(b[2],d[2]);vec3.add(b[0],vec3.add(b[1],b[2]),c);vec3.normalize(c);return c};HG.Trackball.prototype.projectBall=function(a,c){var b=vec3.create();b[0]=(a/(this.canvas.width*0.5))-1;b[1]=1-(c/(this.canvas.height*0.5));b[2]=1-b[0]*b[0]-b[1]*b[1];b[2]=b[2]>0?Math.sqrt(b[2]):0;return b};AC.Triangulator=function(){};AC.Triangulator.triangulate=function(e){var m=[],l=[],d=e.length,g=d-1,c=2*d,b=0,a,k,f,h;h=AC.Triangulator.ccw(e)>0;for(;b<d;++b){l.push(h?b:d-b-1)}while(d>2){if(c--<=0){return null}k=g;g=k+1;if(g>=d){g=0}f=g+1;if(f>=d){f=0}if(AC.Triangulator.snip(e,k,g,f,d,l)){m.push(l[k],l[g],l[f]);for(a=g+1;a<d;++a){l[a-1]=l[a]}d--;c=2*d}}return h?m:m.reverse()};AC.Triangulator.ccw=function(e){var c=0,b=e.length,f=b-1,d=0;for(;d<b;f=d++){c+=e[f].x*e[d].y-e[d].x*e[f].y}return c};AC.Triangulator.snip=function(d,j,h,f,r,c){var g=d[c[j]].x,e=d[c[j]].y,s=d[c[h]].x,q=d[c[h]].y,b=d[c[f]].x,a=d[c[f]].y,p=0,l,k,o,n,m;if((s-g)*(a-e)-(q-e)*(b-g)<1e-10){return false}for(;p<r;++p){if((p!==j)&&(p!==h)&&(p!==f)){l=d[c[p]].x;k=d[c[p]].y;o=(b-s)*(k-q)-(a-q)*(l-s);n=(s-g)*(k-e)-(q-e)*(l-g);m=(g-b)*(k-a)-(e-a)*(l-b);if((o>=0)&&(n>=0)&&(m>=0)){return false}}}return true};HG.Viewer=function(a){this.canvas=a;this.renderer=new HG.Renderer(a)};HG.Viewer.prototype.show=function(a,b){b.filename=a;HG.Loader.loadText(a,this,"onModelLoaded",b)};HG.Viewer.prototype.onModelLoaded=function(c,e){var a=new AC.File(c),d=new HG.Scene(a),b=new HG.Camera(e.setup||this.fitToBoundingBox(d));this.renderer.setScene(e.texturePath||this.getPath(e.filename),d,b);this.trackball=new HG.Trackball(this.canvas,b);this.tick();e.callback()};HG.Viewer.prototype.tick=function(){var a=this;requestAnimationFrame(function(){a.tick()});this.renderer.render()};HG.Viewer.prototype.onResize=function(b,a){this.renderer.resize(b,a)};HG.Viewer.prototype.fitToBoundingBox=function(c){var a={},d=c.boundingBox,b=vec3.create(),e;a.eye=vec3.create();a.poi=vec3.create();a.up=[0,1,0];a.fov=45;a.eye[0]=d.xmin;a.eye[1]=d.ymax;a.eye[2]=d.zmax;a.poi[0]=(d.xmax+d.xmin)*0.5;a.poi[1]=(d.ymax+d.ymin)*0.5;a.poi[2]=(d.zmax+d.zmin)*0.5;vec3.subtract(a.eye,a.poi,b);e=vec3.length(b)/(Math.tan(a.fov*(Math.PI/180)*0.5));vec3.normalize(b);vec3.scale(b,e);a.eye=b;return a};HG.Viewer.prototype.getPath=function(b){var c="",a;a=b.lastIndexOf("/");if(-1!==a){c=b.substring(0,a+1)}return c};
