/*
 Descri��o:
 - Cont�m fun��es de montagem de menu

 Depend�ncias:
 - biblioteca_funcoes_principal.js
*/

//_pastaImagensGlobal eh variavel global definida em config_lib.php
//pois o local da imagem dependera do local da pagina php
var pastaImagens = _pastaImagensGlobal;

var _treeCount = 0;
var _folderIcons = new Array(pastaImagens + "arrowhead1.gif", pastaImagens + "arrowhead1down.gif", pastaImagens + "arrowhead2.gif");
var _linkIcon = pastaImagens + "arrowhead2.gif";
var _allSeqItems = new Array();
var _allHRefItems = new Array();
var _allLinkArquivo = new Array();

function getNmCookieTree() {
	nmCookie = "sfi.treemenu";
	try {
		nmCookie = getNmCookieTreeMenu();
	} catch(e) {
	}

	return nmCookie;
}

function chavearItemTree(id) {
    var div = document.getElementById('div' + id);
    var img = document.getElementById('img' + id);
    
    if (div.style.display == 'none') {
    	expandirItemTree(id);
    } else {
    	retrairItemTree(id);
    }
}

function storeStatus(id, status, expires) {
	var oldValue = getCookie(getNmCookieTree());
	var myKey = "tid" + id + "@";
	//alert("expandirItemTree.oldValue: " + oldValue);
	//alert("expandirItemTree.typeof oldValue: " + typeof oldValue);

	if (typeof oldValue != "undefined") {
		offset = oldValue.indexOf(myKey);
		//alert("expandirItemTree.offset: " + offset);
		if (offset != -1) {
			newValue = oldValue.substring(0, offset);
			//alert("expandirItemTree.newValue1: " + newValue);
		    newValue = newValue + myKey + status + oldValue.substring(offset + myKey.length + 1, oldValue.length);
			//alert("expandirItemTree.newValue2: " + newValue);
	    } else {
			newValue = oldValue + myKey + status;
			//alert("expandirItemTree.newValue: " + newValue);
		}
	} else {
		newValue = myKey + status;
	}
	
	//alert("storeStatus.newValue: " + newValue);
	setCookie(getNmCookieTree(), newValue, expires);
}

function expandirItemTree(id) {
    var today = new Date();
    var expires = new Date();
    expires.setTime(today.getTime() + 1000*60*60*24*365);

    var div = document.getElementById('div' + id);
    var img = document.getElementById('img' + id);
    
    if (div != null) {
	    div.style.display = '';
	    img.src = _folderIcons[1];

	    storeStatus(id, '1', expires);
    }
}

function retrairItemTree(id) {
    var today = new Date();
    var expires = new Date();
    expires.setTime(today.getTime() + 1000*60*60*24*365);

	var div = document.getElementById('div' + id);
	var img = document.getElementById('img' + id);

    if (div != null) {
	    div.style.display = 'none';
	    img.src = _folderIcons[0];
	
	    storeStatus(id, '0', expires);
    }
}

function LinkArquivo(label, href, pInJanelaAuxiliar, pNmClasseCSS) {
	var seq = "";
	var background = "";
	var pValue = "";
	var pInSelecionado = "";
	var pInComParametrosFramework = "";
	
	Link(label, href, seq, background, pInJanelaAuxiliar, pValue, pInSelecionado, pNmClasseCSS, pInComParametrosFramework, true);
}

function Link(label, href, seq, background, pInJanelaAuxiliar, pValue, pInSelecionado, pNmClasseCSS, pInComParametrosFramework, isLinkArquivo) {

    // properties
    this.label = label;
    this.href = href;

    if (seq == null) {
    	this.seq = "";
    } else {
    	this.seq = seq;
    }

    this.background = background;

    if (background == null) {
        this.background = '#A5B9D7';
    } else {
        this.background = background;
    }

    if (pInJanelaAuxiliar == true) {
	    this.inJanelaAuxiliar = true;
    } else {
		this.inJanelaAuxiliar = false;
	}

    this.value = pValue;
    this.inSelecionado = pInSelecionado;
    
    if (pNmClasseCSS == null || pNmClasseCSS == '') {
        this.nmClasseCSS = "treelink";
    } else {
        this.nmClasseCSS = pNmClasseCSS;
   	}

    if (pInComParametrosFramework == true) {
	    this.inComParametrosFramework = true;
    } else {
		this.inComParametrosFramework = false;
	}

    this.classname = 'Link'
    
    _allSeqItems[_allSeqItems.length] = seq;
    _allHRefItems[_allHRefItems.length] = href;
    
    if(isLinkArquivo == null)
    	isLinkArquivo = false;
    
    _allLinkArquivo[_allLinkArquivo.length] = isLinkArquivo;
}
// End Class - Link

// Begin Class - Tree
function Tree(label, open, id, background, pValorTree, pInTemSubNiveis, pInColocarRadioTree, pCdMenu, pNmClasseCSS) {

    // properties
    this.label = label;
    this.items = new Array();

    if (id == null) {
        this.id = _treeCount++;
    } else {
        this.id = id;
    }

    if (open == null) {
		var cookie = getCookie(getNmCookieTree());
		//alert("Tree.cookie: " + cookie);
		var myKey = "tid" + this.id + "@";
        //alert("Tree.mykey: " + myKey);
        if (typeof cookie != "undefined") {
			offset = cookie.indexOf(myKey);
			if (offset != -1) {
				val = cookie.substring(offset + myKey.length, offset + myKey.length + 1);
				if (val == "0") {
					this.open = false;
				} else { // use cookie definition
					//alert("Tree.val: " + val);
	                this.open = true;
				}
			} else {
				this.open = false;
			}
		} else {
        	this.open = false;
        }
    } else {
        this.open = open;
    }
    
    if (background == null) {
        this.background = '#A5B9D7';
    } else {
        this.background = background;
    }

    if (pValorTree == null) {
    	this.valorTree = "";
	} else {
	    this.valorTree = pValorTree;
	}

    if (pInTemSubNiveis == null) {
    	this.inTemSubNiveis = true;
	} else {
	    this.inTemSubNiveis = pInTemSubNiveis;
	}
    
    this.inColocarRadioTree = pInColocarRadioTree;
    this.cdMenu = pCdMenu;

    if (pNmClasseCSS == null || pNmClasseCSS == '') {
        //this.nmClasseCSS = "treelink";
        this.nmClasseCSS = "titulotreemenu";        
    } else {
        this.nmClasseCSS = pNmClasseCSS;
   	}

    this.classname = 'Tree'

    // methods
    this.adicionarItem = adicionarItem;
    this.expandirTudo = expandirTudo;
    this.retrairTudo = retrairTudo;
    this.escrever = escrever;
    this.escreverCheckbox = escreverCheckbox;
    this.escreverInterno = escreverInterno;
    this.getHRefLink = getHRefLink;
}

function adicionarItem(item, link) {
    this.items[this.items.length] = item;
}

function expandirTudo() {
	if (this.inTemSubNiveis) {
		expandirItemTree(this.id);
	}

	var i;

    for (i = 0; i < this.items.length; i++) {
    	if (this.items[i].classname == 'Tree') {
    		this.items[i].expandirTudo();
    	}
    }
}

function retrairTudo() {
	if (this.inTemSubNiveis) {
		retrairItemTree(this.id);
	}

	var i;

    for (i = 0; i < this.items.length; i++) {
    	if (this.items[i].classname == 'Tree') {
    		this.items[i].retrairTudo();
    	}
    }
}

function getHRefLink(seq) {
	var href = "";
	
    for (i = 0; i < _allSeqItems.length; i++) {
    	if (_allSeqItems[i] == seq) {
    		href = _allHRefItems[i];
    		return href;
    	}
    }
    
    return href;
}

function escrever(printLabel, level, pInColocarRadioLink, pNmFuncaoJS) {
	this.escreverInterno(printLabel, level, pInColocarRadioLink, false, pNmFuncaoJS);
}

function escreverCheckbox(printLabel, level, pInColocarCheckLink, pNmFuncaoJS) {
	this.escreverInterno(printLabel, level, false, pInColocarCheckLink, pNmFuncaoJS);
}

function escreverInterno(printLabel, level, pInColocarRadioLink, pInColocarCheckLink, pNmFuncaoJS) {
    var i = 0;

    if (printLabel) {
    	html = '';
    	if (this.inColocarRadioTree) {
	 		html = '<table cellpadding="0" cellspacing="0" width="100%"><tr><td width="99%">';    
	 	}
        html = html + '<div style="margin-bottom: 0px; margin-top: 0px; background: ' 
            + this.background 
            + '; padding-left: ' 
            + (14 * level);
            		
		if (pNmFuncaoJS == null || (typeof pNmFuncaoJS) == "undefined") {
			html = html + 'px;" onclick="chavearItemTree(\'' 
            	+ this.id 
            	+ '\')">';
		} else {
	    	if (this.inTemSubNiveis) {
				html = html + 'px;" onclick="' + pNmFuncaoJS + '(\'' + this.id + '\',\'' + this.valorTree + '\')">';
			} else {
				html = html + 'px;">';
			}
		}

        html = html + '<img id="img' 
            + this.id
            + '" src="';

		if (pNmFuncaoJS == null || (typeof pNmFuncaoJS) == "undefined") {
	        html = html + _folderIcons[this.open?1:0] + '">';
 	  		if (this.inColocarRadioTree) {
				html = html + '<input type="radio" name="rdb_tree" value="' + this.valorTree + '">';
 	  		}	        
	        html = html + '<a class="' + this.nmClasseCSS +'" href="#0" style="text-decoration: none;">';
	    } else {
	    	if (this.inTemSubNiveis) {
				if (isNS4 || isNS6) {
 	  		        html = html + _folderIcons[this.open?1:0] + '">';
 	  		        if (this.inColocarRadioTree) {
 	  		        	html = html + '<input type="radio" name="rdb_tree" value="' + this.valorTree + '">';
 	  		        }
 	  		        html = html + '<a class="' + this.nmClasseCSS +'" name="' + this.id + '" onclick="event.stopPropagation();" href="javascript:' + pNmFuncaoJS + '(\'' + this.id + '\',\'' + this.valorTree + '\');" style="text-decoration: none;">';
			    } else {
		 	        html = html + _folderIcons[this.open?1:0] + '">';
		 	        if (this.inColocarRadioTree) {
						html = html + '<input type="radio" name="rdb_tree" value="' + this.valorTree + '">';
 	  		        }
		 	        html = html + '<a class="' + this.nmClasseCSS +'" name="' + this.id + '" onclick="event.cancelBubble = true;" href="javascript:' + pNmFuncaoJS + '(\'' + this.id + '\',\'' + this.valorTree + '\');" style="text-decoration: none;">';
			    }
			} else {
  		        html = html +  _folderIcons[2] + '">';
  		        if (this.inColocarRadioTree) {
  		        	html = html + '<input type="radio" name="rdb_tree" value="' + this.valorTree + '">';
  		        }
  		        html = html + '<a class="' + this.nmClasseCSS +'" name="' + this.id + ' onclick="" + href="#0" style="text-decoration: none;">';
			}
	    }

        html = html + this.label + '</a></div>';
		if (this.inColocarRadioTree) {
        	html = html + '</td><td style="background: '
            	+ this.background + '">'        
	       		+ this.cdMenu + '</td></tr></table>';	       
	    }

        document.write(html);

        if (this.open) {
            html = '<div id="div' 
                + this.id 
                + '">';
        } else {
            html = '<div id="div' 
                + this.id 
                + '" style="display: none;">';
        }

        document.write(html);
    }

    for (i = 0; i < this.items.length; i++) {
        if (this.items[i].classname == 'Tree') {
			if (pNmFuncaoJS == null || (typeof pNmFuncaoJS) == "undefined") {
            	this.items[i].escreverInterno(true, level + 1, pInColocarRadioLink, pInColocarCheckLink);
	        } else {
	            this.items[i].escreverInterno(true, level + 1, pInColocarRadioLink, pInColocarCheckLink, pNmFuncaoJS);
	        }
        } else {
        	html = '<table cellpadding="0" cellspacing="0" width="100%"><tr><td width="99%"><div style="margin-bottom: 0px; margin-top: 0px; background: '
                + this.items[i].background 
                + '; padding-left : ' 
                + (14 * (level + 1)) 
                + 'px;"><img border="0" src="'
                + _linkIcon + '">';

			if (pInColocarRadioLink) {
				html = html + '<input type="radio" name="rdb_tree" value="' + this.items[i].value + '">';

			} else if (pInColocarCheckLink) {
				if (this.items[i].inSelecionado){
					html = html + '<input type="checkbox" name="chk_tree" value="' + this.items[i].value + '" checked>';
				} else {
					html = html + '<input type="checkbox" name="chk_tree" value="' + this.items[i].value + '">';
				}
			}
			
			if (this.items[i].href != null) {
				
				//se nao for link arquivo
				if(!_allLinkArquivo[i]){
					//original
					html = html + '<a class="' + this.items[i].nmClasseCSS +'" href="javascript:ativarItemMenu(\'' + this.items[i].href + '\', \'' + this.items[i].seq + '\', ' + this.items[i].inJanelaAuxiliar + ', ' + this.items[i].inComParametrosFramework + ')" style="text-decoration : none;">';
				}else{
										
					//html = html + '<a class="' + this.items[i].nmClasseCSS +'" href="'+ this.items[i].href +'" target="_blank" style="text-decoration : none;">';
					html = html + '<a class="' + this.items[i].nmClasseCSS +'" href="../abrir_windowsexplorer.php?comando='+ this.items[i].href +'" target="_blank" style="text-decoration : none;">';
					
				    //url = "abrir_windowsexplorer.php?comando=" + this.items[i].href;
				    //abrirJanelaAuxiliar(url, true, false, false);					
					
				}
				
			} else {
				html = html + '<a class="' + this.items[i].nmClasseCSS +'" href="#0" style="text-decoration: none;">';
			}
			
			html = html +  this.items[i].label 
          		+ '</a></div>' 
          		+ '</td><td style="background: '
            	+ this.items[i].background + '">' + this.items[i].seq + '</td></tr></table>';
              
            document.write(html);
        }
    }

    if (printLabel) {
        document.write('</div>');
    }
}
// End Class - Tree
