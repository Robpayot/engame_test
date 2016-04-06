(function() {


    var _container = document.createElement('div');
    var _content = document.createElement('p');
    _content.setAttribute('style', 'position:relative;top:50%');
    _content.innerHTML = 'Votre page est en chargement <br>Voici une petite animation';
    _container.appendChild(_content);
    _container.setAttribute('style', 'color:black;transition: opacity ease-out 300ms;position:fixed;top:0;left:0;z-index:-99999;opacity:0;width:100%;height:100%;background:#fff;text-align:center;transform:translateX(-100%)');

    return {

        obj: null,

        create: function(instantDraw){
            var self = this;
            self.obj = _container;
            //console.log('create obj',this.obj);
            if(instantDraw){
                self.obj.style.zIndex = '99999';
                self.obj.style.transform = 'translateX(0)';
                self.obj.style.opacity = 1;
                //self.obj.style.background = '#cc0000';
            }
            window.requestAnimationFrame(function(){
                document.body.appendChild(self.obj);
            });
        },

        draw: function(){
            var self = this;
            //console.log('anim in obj', this.obj);
            window.requestAnimationFrame(function(){
                self.obj.style.zIndex = '99999';
                self.obj.style.transform = 'translateX(0)';
                self.obj.style.opacity = 1;
                //setTimeout(function(){
                    //self.obj.style.background = '#cc0000';
                //}, 1000);

            });
        },

        dispose: function(){
            var self = this;
            //console.log('anim out obj', this.obj);
            window.requestAnimationFrame(function(){
                //self.obj.style.transitionDuration = "600ms";
                self.obj.style.transitionTimingFunction = "ease-in";
                self.obj.style.opacity = 0;
                setTimeout(function(){
                    window.requestAnimationFrame(function(){
                        self.obj.style.transform = 'translateX(100%)';
                        self.obj.style.zIndex = '-99999';
                    });
                }, 300);
            });
        },

        destroy: function(){
            //console.log('destroy obj', this.obj);
            var self = this;
            window.requestAnimationFrame(function(){
                document.body.removeChild(self.obj);
            });
        },

        playAnim: function(){
            console.log('Animation is playing');
        }
    }

})
