"use strict";
        class Alert {
        /*constructor() {
         this.msg=msg;
         // this.position=position?position:'bottom';
         // this.duration=duration?duration:4000;
         }*/
        returnMensagem(msg,position,tipo,duration){
        //var $this =self.alert;
        this.msg=msg;
                this.position=position?position:'bottom';
                this.tipo=tipo?tipo:'standard';
                this.duration=duration?duration:4000;
                return this.criaElement();
        }

        criaElement(){
        var body=document.querySelector('body'),div,msg,text,close;
                div=document.createElement('DIV')
                div.classList.add('alert');
                div.classList.add(this.tipo);
                div.classList.add(this.position);
                msg=document.createElement('DIV');
                msg.classList.add('boxMsg');
                text=document.createElement('SPAN');
                text.classList.add('msg');
                text.innerHTML=this.msg;
                close=document.createElement('span');
                close.classList.add('close');
                close.onclick=function(){
                this.closest('.alert').classList.add('deleting');
                };
                msg.append(text);
                msg.append(close);
                div.append(msg);
                setTimeout(function(){
                div.classList.add('deleting');
                },this.duration);
                setTimeout(function(){
                div.remove();
//                div.style.display='none';
                },this.duration);
                body.append(div);
                // return body;
                return true;
        }
        }
