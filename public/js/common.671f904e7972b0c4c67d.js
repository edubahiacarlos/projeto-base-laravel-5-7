(window.webpackJsonp=window.webpackJsonp||[]).push([[0],{jiMb:function(t,n,e){"use strict";e.d(n,"a",function(){return p});var i=e("mrSG"),a=e("t9fZ"),o=e("AytR"),r=e("XNvx"),c=e("lxRa"),u=e("CcnG"),s=e("t/Na"),p=function(t){function n(n,e){var i=t.call(this,n,o.a.API+"api/perfis",e)||this;return i.http=n,i.loginService=e,i}return i.c(n,t),n.prototype.dadosPerfilDominio=function(){return this.http.get(o.a.API+"api/perfildadosdominio",this.getCabecalho()).pipe(Object(a.a)(1))},n.prototype.buscaAcoesPossiveis=function(){return[{slug:"criar",nome:"Criar"},{slug:"atualizar",nome:"Atualizar"},{slug:"apagar",nome:"Apagar"},{slug:"visualizar",nome:"Visualizar"}]},n.prototype.buscaFuncionalidades=function(){return[{slug:"usuario",nome:"Usu\xe1rio"},{slug:"perfil",nome:"Perfil"}]},n.ngInjectableDef=u.defineInjectable({factory:function(){return new n(u.inject(s.c),u.inject(r.a))},token:n,providedIn:"root"}),n}(c.a)},rILs:function(t,n,e){"use strict";e.d(n,"a",function(){return p});var i=e("mrSG"),a=e("t9fZ"),o=e("XNvx"),r=e("AytR"),c=e("lxRa"),u=e("CcnG"),s=e("t/Na"),p=function(t){function n(n,e){var i=t.call(this,n,r.a.API+"api/acoes",e)||this;return i.http=n,i.loginService=e,i}return i.c(n,t),n.prototype.dadosAcaoDominio=function(){return this.http.get(r.a.API+"api/acaodadosdominio",this.getCabecalho()).pipe(Object(a.a)(1))},n.ngInjectableDef=u.defineInjectable({factory:function(){return new n(u.inject(s.c),u.inject(o.a))},token:n,providedIn:"root"}),n}(c.a)}}]);