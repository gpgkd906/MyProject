/**
 * 格式刷，只格式inline的
 * @function
 * @name baidu.editor.commands.formatmatch
 * @author zhanyi
 */
(function() {

    var domUtils = baidu.editor.dom.domUtils,
        list = [],img,
        flag = 0;

    baidu.editor.commands['formatmatch'] = {
        execCommand : function( cmdName ) {
            var me = this;
            if(flag){
                flag = 0;
                list = [];
                 me.removeListener('mouseup',addList);
                return;
            }

            function addList(){
               
                function addFormat(range){
                    
                    if(text && (!me.currentSelectedArr || !me.currentSelectedArr.length)){
                        range.selectNode(text);
                    }
                    return range.applyInlineStyle(list[list.length-1].tagName,null,list);
                    
                }

                me.undoManger && me.undoManger.save();

                var range = me.selection.getRange(),
                    imgT = range.getClosedNode();
                if(img && imgT && imgT.tagName == 'IMG'){
                    imgT.style.cssText += ';' + img.style.cssText;
                    img = null;
                }else{
                    if(!img){
                        if(range.collapsed){
                            var text = me.document.createTextNode('match');
                            range.insertNode(text).select();


                        }
                        me.__hasEnterExecCommand = true;
                        me.execCommand('removeformat');
                        me.__hasEnterExecCommand = false;
                        //trace:969
                        range = me.selection.getRange();
                        if(list.length == 0){

                            if(me.currentSelectedArr && me.currentSelectedArr.length > 0){
                                range.selectNodeContents(me.currentSelectedArr[0]).select();
                            }
                        }else{
                            if(me.currentSelectedArr && me.currentSelectedArr.length > 0){

                                for(var i=0,ci;ci=me.currentSelectedArr[i++];){
                                    range.selectNodeContents(ci);
                                    addFormat(range);

                                }
                                range.selectNodeContents(me.currentSelectedArr[0]).select();
                            }else{


                                addFormat(range)

                            }
                        }
                        if(!me.currentSelectedArr || !me.currentSelectedArr.length){
                            if(text){
                                range.setStartBefore(text).collapse(true);

                            }

                            range.select()
                        }
                        text && domUtils.remove(text);
                    }

                }


               

                me.undoManger && me.undoManger.save();
                me.removeListener('mouseup',addList);
                flag = 0;
            }

              
            var range = me.selection.getRange();
            img = range.getClosedNode();
            if(!img || img.tagName != 'IMG'){
               range.collapse(true).shrinkBoundary();
               var start = range.startContainer;
               list = domUtils.findParents(start,true,function(node){
                   return !domUtils.isBlockElm(node) && node.nodeType == 1
               });
               //a不能加入格式刷, 并且克隆节点
               for(var i=0,ci;ci=list[i];i++){
                   if(ci.tagName == 'A'){
                       list.splice(i,1);
                       break;
                   }
               }

            }

            me.addListener('mouseup',addList);
            flag = 1;


        },
        queryCommandState : function() {
            return flag;
        },
        notNeedUndo : 1
    }
})();

