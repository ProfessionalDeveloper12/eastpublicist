/**
 * jscolor, JavaScript Color Picker
 *
 * @version 1.3.13
 * @license GNU Lesser General Public License, http://www.gnu.org/copyleft/lesser.html
 * @author  Jan Odvarko, http://odvarko.cz
 * @created 2008-06-15
 * @updated 2012-01-19
 * @link    http://jscolor.com
 */
	
var id = function(i){return document.getElementById(i);};
var jscolor = {
	
	name2Hex : function(colour)
	{
	    var colours = {"aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff","beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887","cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff","darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f","darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1","darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff","firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff","gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f","honeydew":"#f0fff0","hotpink":"#ff69b4","indianred ":"#cd5c5c","indigo":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c","lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2","lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de","lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6","magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee","mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5","navajowhite":"#ffdead","navy":"#000080","oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6","palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080","red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1","saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4","tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0","violet":"#ee82ee","wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5","yellow":"#ffff00","yellowgreen":"#9acd32"};
	
	    if (typeof colours[colour.toLowerCase()] != 'undefined')
	        return colours[colour.toLowerCase()];
	
	    return false;
	},

	
	HEX : {
		R : function(h){return parseInt((this.cutHex(h)).substring(0,2),16)},
		G : function(h){return parseInt((this.cutHex(h)).substring(2,4),16)},
		B : function(h){return parseInt((this.cutHex(h)).substring(4,6),16)},
		O : function(c){
			if(c.indexOf("#") > -1 || c.indexOf("(") === -1 || c.indexOf(")") === -1)
				return 1; 
			return c.split('(')[1].split(')')[0].split(',')[3];
		},
		cutHex : function(h){return (h.charAt(0)=="#") ? h.substring(1,7):h},
	},

	dir : '', // location of jscolor directory (leave empty to autodetect)
	bindClass : 'color', // class name
	binding : true, // automatic binding via <input class="...">
	preloading : true, // use image preloading?


	install : function() {
		jscolor.addEvent(window, 'load', jscolor.init);
	},


	init : function() {
		if(jscolor.binding) {
			jscolor.bind();
		}
		if(jscolor.preloading) {
			jscolor.preload();
		}
	},


	getDir : function() {
		if(!jscolor.dir) {
			var detected = jscolor.detectDir();
			jscolor.dir = detected!==false ? detected : 'jscolor/';
		}
		return jscolor.dir;
	},


	detectDir : function() {
		var base = location.href;

		var e = document.getElementsByTagName('base');
		for(var i=0; i<e.length; i+=1) {
			if(e[i].href) { base = e[i].href; }
		}

		var e = document.getElementsByTagName('script');
		for(var i=0; i<e.length; i+=1) {
			if(e[i].src && /(^|\/)jscolor\.js([?#].*)?$/i.test(e[i].src)) {
				var src = new jscolor.URI(e[i].src);
				var srcAbs = src.toAbsolute(base);
				srcAbs.path = srcAbs.path.replace(/[^\/]+$/, ''); // remove filename
				srcAbs.query = null;
				srcAbs.fragment = null;
				return srcAbs.toString();
			}
		}
		return false;
	},


	bind : function() {
		var matchClass = new RegExp('(^|\\s)('+jscolor.bindClass+')\\s*(\\{[^}]*\\})?', 'i');
		var e = document.getElementsByTagName('input');
		for(var i=0; i<e.length; i+=1) {
			var m;
			if(!e[i].color && e[i].className && (m = e[i].className.match(matchClass))) {
				var prop = {};
				if(m[3]) {
					try {
						eval('prop='+m[3]);
					} catch(eInvalidProp) {}
				}
				e[i].color = new jscolor.color(e[i], prop);
			}
		}
	},


	preload : function() {
		for(var fn in jscolor.imgRequire) {
			if(jscolor.imgRequire.hasOwnProperty(fn)) {
				jscolor.loadImage(fn);
			}
		}
	},


	images : {
		pad : [ 181, 101 ],
		sld : [ 16, 101 ],
		cross : [ 15, 15 ],
		arrow : [ 7, 11 ]
	},


	imgRequire : {},
	imgLoaded : {},


	requireImage : function(filename) {
		jscolor.imgRequire[filename] = true;
	},


	loadImage : function(filename) {
		if(!jscolor.imgLoaded[filename]) {
			jscolor.imgLoaded[filename] = new Image();
			jscolor.imgLoaded[filename].src = jscolor.getDir()+filename;
		}
	},


	fetchElement : function(mixed) {
		return typeof mixed === 'string' ? document.getElementById(mixed) : mixed;
	},


	addEvent : function(el, evnt, func) {
		if(el.addEventListener) {
			el.addEventListener(evnt, func, false);
		} else if(el.attachEvent) {
			el.attachEvent('on'+evnt, func);
		}
	},


	fireEvent : function(el, evnt) {
		if(!el) {
			return;
		}
		if(document.createEvent) {
			var ev = document.createEvent('HTMLEvents');
			ev.initEvent(evnt, true, true);
			el.dispatchEvent(ev);
		} else if(document.createEventObject) {
			var ev = document.createEventObject();
			el.fireEvent('on'+evnt, ev);
		} else if(el['on'+evnt]) { // alternatively use the traditional event model (IE5)
			el['on'+evnt]();
		}
	},


	getElementPos : function(e) {
		var e1=e, e2=e;
		var x=0, y=0;
		if(e1.offsetParent) {
			do {
				x += e1.offsetLeft;
				y += e1.offsetTop;
			} while(e1 = e1.offsetParent);
		}
		while((e2 = e2.parentNode) && e2.nodeName.toUpperCase() !== 'BODY') {
			x -= e2.scrollLeft;
			y -= e2.scrollTop;
		}
		return [x, y];
	},


	getElementSize : function(e) {
		return [e.offsetWidth, e.offsetHeight];
	},


	getRelMousePos : function(e) {
		var x = 0, y = 0;
		if (!e) { e = window.event; }
		if (typeof e.offsetX === 'number') {
			x = e.offsetX;
			y = e.offsetY;
		} else if (typeof e.layerX === 'number') {
			x = e.layerX;
			y = e.layerY;
		}
		return { x: x, y: y };
	},


	getViewPos : function() {
		if(typeof window.pageYOffset === 'number') {
			return [window.pageXOffset, window.pageYOffset];
		} else if(document.body && (document.body.scrollLeft || document.body.scrollTop)) {
			return [document.body.scrollLeft, document.body.scrollTop];
		} else if(document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
			return [document.documentElement.scrollLeft, document.documentElement.scrollTop];
		} else {
			return [0, 0];
		}
	},


	getViewSize : function() {
		if(typeof window.innerWidth === 'number') {
			return [window.innerWidth, window.innerHeight];
		} else if(document.body && (document.body.clientWidth || document.body.clientHeight)) {
			return [document.body.clientWidth, document.body.clientHeight];
		} else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
			return [document.documentElement.clientWidth, document.documentElement.clientHeight];
		} else {
			return [0, 0];
		}
	},


	URI : function(uri) { // See RFC3986

		this.scheme = null;
		this.authority = null;
		this.path = '';
		this.query = null;
		this.fragment = null;

		this.parse = function(uri) {
			var m = uri.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);
			this.scheme = m[3] ? m[2] : null;
			this.authority = m[5] ? m[6] : null;
			this.path = m[7];
			this.query = m[9] ? m[10] : null;
			this.fragment = m[12] ? m[13] : null;
			return this;
		};

		this.toString = function() {
			var result = '';
			if(this.scheme !== null) { result = result + this.scheme + ':'; }
			if(this.authority !== null) { result = result + '//' + this.authority; }
			if(this.path !== null) { result = result + this.path; }
			if(this.query !== null) { result = result + '?' + this.query; }
			if(this.fragment !== null) { result = result + '#' + this.fragment; }
			return result;
		};

		this.toAbsolute = function(base) {
			var base = new jscolor.URI(base);
			var r = this;
			var t = new jscolor.URI;

			if(base.scheme === null) { return false; }

			if(r.scheme !== null && r.scheme.toLowerCase() === base.scheme.toLowerCase()) {
				r.scheme = null;
			}

			if(r.scheme !== null) {
				t.scheme = r.scheme;
				t.authority = r.authority;
				t.path = removeDotSegments(r.path);
				t.query = r.query;
			} else {
				if(r.authority !== null) {
					t.authority = r.authority;
					t.path = removeDotSegments(r.path);
					t.query = r.query;
				} else {
					if(r.path === '') { // TODO: == or === ?
						t.path = base.path;
						if(r.query !== null) {
							t.query = r.query;
						} else {
							t.query = base.query;
						}
					} else {
						if(r.path.substr(0,1) === '/') {
							t.path = removeDotSegments(r.path);
						} else {
							if(base.authority !== null && base.path === '') { // TODO: == or === ?
								t.path = '/'+r.path;
							} else {
								t.path = base.path.replace(/[^\/]+$/,'')+r.path;
							}
							t.path = removeDotSegments(t.path);
						}
						t.query = r.query;
					}
					t.authority = base.authority;
				}
				t.scheme = base.scheme;
			}
			t.fragment = r.fragment;

			return t;
		};

		function removeDotSegments(path) {
			var out = '';
			while(path) {
				if(path.substr(0,3)==='../' || path.substr(0,2)==='./') {
					path = path.replace(/^\.+/,'').substr(1);
				} else if(path.substr(0,3)==='/./' || path==='/.') {
					path = '/'+path.substr(3);
				} else if(path.substr(0,4)==='/../' || path==='/..') {
					path = '/'+path.substr(4);
					out = out.replace(/\/?[^\/]*$/, '');
				} else if(path==='.' || path==='..') {
					path = '';
				} else {
					var rm = path.match(/^\/?[^\/]*/)[0];
					path = path.substr(rm.length);
					out = out + rm;
				}
			}
			return out;
		}

		if(uri) {
			this.parse(uri);
		}

	},

	/*
	 * Usage example:
	 * var myColor = new jscolor.color(myInputElement)v
	 */

	color : function(target, prop) {


		this.required = false; // refuse empty values?
		this.adjust = true; // adjust value to uniform notation?
		this.hash = true; // prefix color with # symbol?
		this.caps = true; // uppercase?
		this.slider = true; // show the value/saturation slider?
		this.valueElement = target; // value holder
		this.styleElement = target; // where to reflect current color
		this.onImmediateChange = null; // onchange callback (can be either string or function)
		this.hsv = [0, 0, 1]; // read-only  0-6, 0-1, 0-1
		this.rgb = [1, 1, 1]; // read-only  0-1, 0-1, 0-1

		this.pickerOnfocus = true; // display picker on focus?
		this.pickerMode = 'HSV'; // HSV | HVS
		this.pickerPosition = 'bottom'; // left | right | top | bottom
		this.pickerSmartPosition = true; // automatically adjust picker position when necessary
		this.pickerButtonHeight = 0; // px
		this.pickerClosable = false;
		this.pickerCloseText = 'Close';
		this.pickerButtonColor = 'ButtonText'; // px
		this.pickerFace = 15; // px
		this.pickerFaceColor = 'rgba(70, 85, 89, 0.97)'; // CSS color
		this.pickerBorder = 0; // px
		this.pickerBorderColor = ''; // CSS color
		this.pickerInset = 0; // px
		this.pickerInsetColor = ''; // CSS color
		this.pickerZIndex = 180000002;
		this.opacity = 1;
		this.position = 'absolute';

		for(var p in prop) {
			if(prop.hasOwnProperty(p)) {
				this[p] = prop[p];
			}
		}

		if (kc !== undefined) {
			var pop = kc.get.popup(target);
			if (pop !== null) {
				if (pop.css('position') == 'fixed')
					this.position = 'fixed';
			}
		}

		this.hidePicker = function() {
			if(isPickerOwner()) {
				removePicker();
			}
		};

		this.showPicker = function() {
			
			if(!isPickerOwner()) {
				
				var tp = jscolor.getElementPos(target); // target pos
				var ts = jscolor.getElementSize(target); // target size
				var vp = jscolor.getViewPos(); // view pos
				var vs = jscolor.getViewSize(); // view size
				var ps = getPickerDims(this); // picker size
				var a, b, c;
				
				switch(this.pickerPosition.toLowerCase()) {
					case 'left': a=1; b=0; c=-1; break;
					case 'right':a=1; b=0; c=1; break;
					case 'top':  a=0; b=1; c=-1; break;
					default:     a=0; b=1; c=1; break;
				}
				var l = (ts[b]+ps[b])/2;
				
				if( window.kc === undefined )
					return;
				// picker pos
				if (!this.pickerSmartPosition) {
					var pp = [
						tp[a],
						tp[b]+ts[b]-l+l*c
					];
				} else {
					var pp = [
						-vp[a]+tp[a]+ps[a] > vs[a] ?
							(-vp[a]+tp[a]+ts[a]/2 > vs[a]/2 && tp[a]+ts[a]-ps[a] >= 0 ? tp[a]+ts[a]-ps[a] : tp[a]) :
							tp[a],
						-vp[b]+tp[b]+ts[b]+ps[b]-l+l*c > vs[b] ?
							(-vp[b]+tp[b]+ts[b]/2 > vs[b]/2 && tp[b]+ts[b]-l-l*c >= 0 ? tp[b]+ts[b]-l-l*c : tp[b]+ts[b]-l+l*c) :
							(tp[b]+ts[b]-l+l*c >= 0 ? tp[b]+ts[b]-l+l*c : tp[b]+ts[b]-l-l*c)
					];
				}
				drawPicker(pp[a]+1, pp[b]+1);
			}
		};

		this.importColor = function() {
			
			if(window.kc === undefined)
				return;
				
			if(!valueElement) {
				this.exportColor();
			} else {

				if( valueElement.value === '' ){
					this.fromString(valueElement.value);
					return;
				}

				if( valueElement.value.indexOf('#') == -1 ){
					this.fromString(valueElement.value);
					this.exportColor('import');
					return;
				}

				if(!this.adjust) {
					if(!this.fromString(valueElement.value, leaveValue)) {
						styleElement.style.backgroundImage = styleElement.jscStyle.backgroundImage;
						styleElement.style.backgroundColor = styleElement.jscStyle.backgroundColor;
						styleElement.style.color = styleElement.jscStyle.color;
						this.exportColor(leaveValue | leaveStyle);
					}
				} else if(!this.required && /^\s*$/.test(valueElement.value)) {
					valueElement.value = '';
					styleElement.style.backgroundImage = styleElement.jscStyle.backgroundImage;
					styleElement.style.backgroundColor = styleElement.jscStyle.backgroundColor;
					styleElement.style.color = styleElement.jscStyle.color;
					this.exportColor(leaveValue | leaveStyle);

				} else if(this.fromString(valueElement.value)) {
					// OK
				} else {
					this.exportColor();
				}
			}
		};

		this.exportColor = function(flags) {
			
			if( window.kc === undefined )
				return;
			// do not use opacity
			if(THIS.opacity == '1' && flags != 'import'){
			
				if(!(flags & leaveValue) && valueElement) {
					var value = this.toString();
					if(this.caps) { value = value.toLowerCase(); }
					if(this.hash) { value = '#'+value; }
					valueElement.value = value;
				}
				if(!(flags & leaveStyle) && styleElement) {
					styleElement.style.backgroundImage = "none";
					styleElement.style.backgroundColor =
						'#'+this.toString();
					styleElement.style.color =
						0.213 * this.rgb[0] +
						0.715 * this.rgb[1] +
						0.072 * this.rgb[2]
						< 0.5 ? '#FFF' : '#000';
				}
				
				styleElement.value = '#'+this.toString();
			
			}else{
			// use opacity	
				styleElement.style.color = 0.213 * this.rgb[0] +
						0.715 * this.rgb[1] +
						0.072 * this.rgb[2]
						< 0.5 ? '#FFF' : '#000';
							
				if( THIS.opacity < 0.5 )
					styleElement.style.color = '#000';
					
				var color = 'rgba('+Math.round(255*THIS.rgb[0])+', '+Math.round(255*THIS.rgb[1])+', '+Math.round(255*THIS.rgb[2])+', '+THIS.opacity.toString().trim()+')';
				
				styleElement.style.backgroundColor = color;
				styleElement.value = color;
				
			}
			
			if(!(flags & leavePad) && isPickerOwner()) {
				redrawPad();
				redrawSld();
				redrawOpacity();
			}
			
			if( jQuery !== undefined )
				jQuery(valueElement).trigger('change');
				
		};

		this.fromHSV = function(h, s, v, flags) { // null = don't change
			h<0 && (h=0) || h>6 && (h=6);
			s<0 && (s=0) || s>1 && (s=1);
			v<0 && (v=0) || v>1 && (v=1);
			this.rgb = HSV_RGB(
				h===null ? this.hsv[0] : (this.hsv[0]=h),
				s===null ? this.hsv[1] : (this.hsv[1]=s),
				v===null ? this.hsv[2] : (this.hsv[2]=v)
			);
			this.exportColor(flags);
		};

		this.fromRGB = function(r, g, b, flags) { // null = don't change
			r<0 && (r=0) || r>1 && (r=1);
			g<0 && (g=0) || g>1 && (g=1);
			b<0 && (b=0) || b>1 && (b=1);
			var hsv = RGB_HSV(
				r===null ? this.rgb[0] : (this.rgb[0]=r),
				g===null ? this.rgb[1] : (this.rgb[1]=g),
				b===null ? this.rgb[2] : (this.rgb[2]=b)
			);
			if(hsv[0] !== null) {
				this.hsv[0] = hsv[0];
			}
			if(hsv[2] !== 0) {
				this.hsv[1] = hsv[1];
			}
			this.hsv[2] = hsv[2];
			this.exportColor(flags);
		};

		this.fromString = function(hex, flags) {
			
			if (jscolor.name2Hex(hex) !== false)
			{
				hex = jscolor.name2Hex(hex);
				styleElement.value = hex;
			}
			
			var m = hex.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i);
			
			if(!m){
				if(hex.indexOf('rgb') > -1 && hex.indexOf("(") > -1 && hex.indexOf(")") > -1){
					
					hex = hex.split("(")[1].split(")")[0].trim().split(',');
					
					this.fromRGB(
						parseInt(hex[0].trim())/255,
						parseInt(hex[1].trim())/255,
						parseInt(hex[2].trim())/255,
						flags
					);
					
					if( hex[3] !== undefined ){
						this.opacity = hex[3].trim()+'000';
						this.opacity = this.opacity.substr(0, 4);
					}else this.opacity = '1';
					
					if( parseFloat(this.opacity) > 1 )
						this.opacity = '1';
					if( parseFloat(this.opacity) === 0 )
						this.opacity = '0';
				}
			} else {
				if(m[1].length === 6) { // 6-char notation
					this.fromRGB(
						parseInt(m[1].substr(0,2),16) / 255,
						parseInt(m[1].substr(2,2),16) / 255,
						parseInt(m[1].substr(4,2),16) / 255,
						flags
					);
				} else { // 3-char notation
					this.fromRGB(
						parseInt(m[1].charAt(0)+m[1].charAt(0),16) / 255,
						parseInt(m[1].charAt(1)+m[1].charAt(1),16) / 255,
						parseInt(m[1].charAt(2)+m[1].charAt(2),16) / 255,
						flags
					);
				}
				return true;
			}
			
			return false;
			
		};

		this.toString = function() {
			return (
				(0x100 | Math.round(255*this.rgb[0])).toString(16).substr(1) +
				(0x100 | Math.round(255*this.rgb[1])).toString(16).substr(1) +
				(0x100 | Math.round(255*this.rgb[2])).toString(16).substr(1)
			);
		};

		function RGB_HSV(r, g, b) {
			var n = Math.min(Math.min(r,g),b);
			var v = Math.max(Math.max(r,g),b);
			var m = v - n;
			if(m === 0) { return [ null, 0, v ]; }
			var h = r===n ? 3+(b-g)/m : (g===n ? 5+(r-b)/m : 1+(g-r)/m);
			return [ h===6?0:h, m/v, v ];
		}

		function HSV_RGB(h, s, v) {
			if(h === null) { return [ v, v, v ]; }
			var i = Math.floor(h);
			var f = i%2 ? h-i : 1-(h-i);
			var m = v * (1 - s);
			var n = v * (1 - s*f);
			switch(i) {
				case 6:
				case 0: return [v,n,m];
				case 1: return [n,v,m];
				case 2: return [m,v,n];
				case 3: return [m,n,v];
				case 4: return [n,m,v];
				case 5: return [v,m,n];
			}
		}

		function removePicker() {
			delete jscolor.picker.owner;
			document.getElementsByTagName('body')[0].removeChild(jscolor.picker.boxB);
		}

		function drawPicker(x, y) {
			
			if(!jscolor.picker) {
				
				jscolor.picker = {
					box : document.createElement('div'),
					boxB : document.createElement('div'),
					pad : document.createElement('div'),
					padB : document.createElement('div'),
					padM : document.createElement('div'),
					sld : document.createElement('div'),
					
					sldB : document.createElement('div'),
					sldBO : document.createElement('div'),
					
					sldM : document.createElement('div'),
					sldO : document.createElement('div'), // Extend for opacity
					
					btn : document.createElement('div'),
					
				};								
				jscolor.picker.boxB.className="kc_color_picker";
				jscolor.picker.boxB.style.position = THIS.position;
							
				for(var i=0,segSize=4; i<jscolor.images.sld[1]; i+=segSize) {
					var seg = document.createElement('div');
					seg.style.height = segSize+'px';
					seg.style.fontSize = '1px';
					seg.style.lineHeight = '0';
					jscolor.picker.sld.appendChild(seg);
				}
				jscolor.picker.sldB.appendChild(jscolor.picker.sld);
				jscolor.picker.box.appendChild(jscolor.picker.sldB);
				jscolor.picker.box.appendChild(jscolor.picker.sldBO);
				jscolor.picker.box.appendChild(jscolor.picker.sldM);
				jscolor.picker.box.appendChild(jscolor.picker.sldO);  // Extend for opacity
				jscolor.picker.padB.appendChild(jscolor.picker.pad);
				jscolor.picker.box.appendChild(jscolor.picker.padB);
				jscolor.picker.box.appendChild(jscolor.picker.padM);
				jscolor.picker.box.appendChild(jscolor.picker.btn);
				jscolor.picker.boxB.appendChild(jscolor.picker.box);
			}

			var p = jscolor.picker;
					
			p.box.id = 'kc-color-picker-box';
			p.pad.id = 'kc-color-picker-pad';
			p.padM.id = 'kc-color-picker-padM';
			p.padB.id = 'kc-color-picker-padB';
			p.boxB.id = 'kc-color-picker-boxB';
			p.sld.id = 'kc-color-picker-sld';
			
			p.sldB.id = 'kc-color-picker-sldB';
			p.sldBO.id = 'kc-color-picker-sldBO';
			
			p.sldM.id = 'kc-color-picker-sldM';
			p.sldO.id = 'kc-color-picker-sldO';
			p.sldO.title = 'Opacity';
			
			p.btn.id = 'kc-color-picker-btn';
			
			// controls interaction
			p.box.onmouseup =
			p.box.onmouseout = function(event) {
				if(event.target.className=='extendRGBA'
					||event.target.parentNode.className=='extendRGBA'
					||event.target.parentNode.parentNode.className=='extendRGBA')
					return;
				target.focus(); 
			};
			p.box.onmousedown = function() { abortBlur=true; };
			p.box.onmousemove = function(e) {
				if (holdPad || holdSld || holdSldO) {
					holdPad && setPad(e);
					holdSld && setSld(e);
					holdSldO && setOpacity(e);
					if (document.selection) {
						document.selection.empty();
					} else if (window.getSelection) {
						window.getSelection().removeAllRanges();
					}
					dispatchImmediateChange();
				}
			};
			p.padM.onmouseup =
			p.padM.onmouseout = function() { if(holdPad) { holdPad=false; jscolor.fireEvent(valueElement,'change'); } };
			p.padM.onmousedown = function(e) {
				// if the slider is at the bottom, move it up
				switch(modeID) {
					case 0: if (THIS.hsv[2] === 0) { THIS.fromHSV(null, null, 1.0); }; break;
					case 1: if (THIS.hsv[1] === 0) { THIS.fromHSV(null, 1.0, null); }; break;
				}
				holdPad=true;
				setPad(e);
				dispatchImmediateChange();
			};
			
			p.sldM.onmouseup =
			p.sldM.onmouseout = function() { if(holdSld) { holdSld=false; jscolor.fireEvent(valueElement,'change'); } };
			p.sldM.onmousedown = function(e) {
				holdSld=true;
				setSld(e);
				dispatchImmediateChange();
			};
			
			p.sldO.onmouseup =
			p.sldO.onmouseout = function() { if(holdSldO) { holdSldO=false; jscolor.fireEvent(valueElement,'change'); } };
			p.sldO.onmousedown = function(e) {
				holdSldO=true;
				setOpacity(e);
				dispatchImmediateChange();
			};

			// picker
			var dims = getPickerDims(THIS);
			p.box.style.width = (dims[0]+40) + 'px';
			p.box.style.height = (dims[1]+80) + 'px';
			
			p.boxB.style.left = x+'px';
			p.boxB.style.top = y+'px';
			
			p.btn.onclick = function (e) {
				
				if(e.target.tagName == 'BUTTON'){
					
					if( typeof( Storage ) !== "undefined" ){
						
						var data = "#61B0FF|#9782FF|#FFD970|#CBFF63|#4DFFAC|#FF21E1|#9021FF|#1F87FF|#21FFBC|#55FF21", 
							color = valueElement.value;
						
						data = data.split('|');
						
						if( localStorage['kc_color_presets'] !== undefined )
							data = localStorage['kc_color_presets'].split('|');
						
						if( color === '' )
							alert('Error, please select color first');
						else if( data.indexOf(color) > -1 )
							alert('Error, this color preset already exists');
						else{
							
							while(data.length > 9)
								data.pop();
								
							data.unshift(color);
							
							localStorage.removeItem( 'kc_color_presets' );
							localStorage.setItem( 'kc_color_presets', data.join('|') );
							
							this.render();
						
						}
						
					}else alert('Your browser does not support offline data');
				
				}else if(e.target.tagName == 'SPAN'){
					valueElement.value = e.target.title;
					redrawOpacity();
					THIS.importColor();
				} else if(e.target.tagName == 'DEL'){
					
					if(localStorage['kc_color_presets'] === undefined || color === '')
						return;
					
					if (localStorage['kc_color_presets'] === '')
						data = "#61B0FF|#9782FF|#FFD970|#CBFF63|#4DFFAC|#FF21E1|#9021FF|#1F87FF|#21FFBC|#55FF21";
					else data = localStorage['kc_color_presets'];
					
					data = data.split('|');
					
					var color = e.target.parentNode.title,
						index = data.indexOf(color);
					
					if (index === -1)
						return;
						
					data.splice(index, 1);
					
					localStorage.removeItem( 'kc_color_presets' );
					localStorage.setItem( 'kc_color_presets', data.join('|') );
					
					this.render();
					
				}
				
				e.preventDefault();
				return false;
			};
			
			p.btn.render = function(){
				
				if( typeof( Storage ) !== "undefined" ){
					
					var data = "#61B0FF|#9782FF|#FFD970|#CBFF63|#4DFFAC|#FF21E1|#9021FF|#1F87FF|#21FFBC|#55FF21",
						i = 0,
						std = ['#000','#fff','#dd3333','#1e73be'],
						html = '<button title="Create new color preset from currently"><i class="fa-plus"></i> Create preset</button>';
					
					data = data.split('|');	
					
					if (localStorage['kc_color_presets'] !== undefined && localStorage['kc_color_presets'] !== '')
						data = localStorage['kc_color_presets'].split('|');
					
					for(i = 0; i < std.length; i++  ){
						html += '<span class="fix-std" title="'+std[i]+'" style="background:'+std[i]+';"></span>';
					}
					html += '<div></div>';
					for(i = 0; i < data.length; i++){
						html += '<span class="preset" title="'+data[i]+'" style="background:'+data[i]+';"><del title="Delete this preset color">x</del></span>';
						if (i>8)
							break;
					}
					
					this.innerHTML = html;
						
				}
			}
			
			p.btn.render();
			
			// load images in optimal order
			switch(modeID) {
				case 0: var padImg = 'hs.png'; break;
				case 1: var padImg = 'hv.png'; break;
			}
			
			p.pad.style.backgroundImage = "url('"+jscolor.getDir()+padImg+"')";
			p.pad.style.backgroundRepeat = "no-repeat";
			p.pad.style.backgroundPosition = "0 0";

			var current = valueElement.value;
			
			var color = THIS.toString();
			
			// place pointers
			redrawPad();
			redrawSld();
			redrawOpacity();
			
 			jscolor.picker.owner = THIS;
			document.getElementsByTagName('body')[0].appendChild(p.boxB);
			
		}

		function getPickerDims(o) {
			var dims = [
				2*o.pickerInset + 2*o.pickerFace + jscolor.images.pad[0] +
					(o.slider ? 2*o.pickerInset + 2*jscolor.images.arrow[0] + jscolor.images.sld[0] : 0),
				o.pickerClosable ?
					4*o.pickerInset + 3*o.pickerFace + jscolor.images.pad[1] + o.pickerButtonHeight :
					2*o.pickerInset + 2*o.pickerFace + jscolor.images.pad[1]
			];
			return dims;
		}

		function redrawPad() {
			// redraw the pad pointer
			switch(modeID) {
				case 0: var yComponent = 1; break;
				case 1: var yComponent = 2; break;
			}
			var x = Math.round((THIS.hsv[0]/6) * (jscolor.images.pad[0]-1));
			var y = Math.round((1-THIS.hsv[yComponent]) * (jscolor.images.pad[1]-1));
			jscolor.picker.padM.style.backgroundPosition =
				(THIS.pickerFace+THIS.pickerInset+x - Math.floor(jscolor.images.cross[0]/2)) + 'px ' +
				(THIS.pickerFace+THIS.pickerInset+y - Math.floor(jscolor.images.cross[1]/2)) + 'px';

			// redraw the slider image
			var seg = jscolor.picker.sld.childNodes;

			switch(modeID) {
				case 0:
					var rgb = HSV_RGB(THIS.hsv[0], THIS.hsv[1], 1);
					for(var i=0; i<seg.length; i+=1) {
						seg[i].style.backgroundColor = 'rgb('+
							(rgb[0]*(1-i/seg.length)*100)+'%,'+
							(rgb[1]*(1-i/seg.length)*100)+'%,'+
							(rgb[2]*(1-i/seg.length)*100)+'%)';
					}
					break;
				case 1:
					var rgb, s, c = [ THIS.hsv[2], 0, 0 ];
					var i = Math.floor(THIS.hsv[0]);
					var f = i%2 ? THIS.hsv[0]-i : 1-(THIS.hsv[0]-i);
					switch(i) {
						case 6:
						case 0: rgb=[0,1,2]; break;
						case 1: rgb=[1,0,2]; break;
						case 2: rgb=[2,0,1]; break;
						case 3: rgb=[2,1,0]; break;
						case 4: rgb=[1,2,0]; break;
						case 5: rgb=[0,2,1]; break;
					}
					for(var i=0; i<seg.length; i+=1) {
						s = 1 - 1/(seg.length-1)*i;
						c[1] = c[0] * (1 - s*f);
						c[2] = c[0] * (1 - s);
						seg[i].style.backgroundColor = 'rgb('+
							(c[rgb[0]]*100)+'%,'+
							(c[rgb[1]]*100)+'%,'+
							(c[rgb[2]]*100)+'%)';
					}
					break;
			}
		}

		function redrawSld() {
			// redraw the slider pointer
			switch(modeID) {
				case 0: var yComponent = 2; break;
				case 1: var yComponent = 1; break;
			}
			var y = Math.round((1-THIS.hsv[yComponent]) * (jscolor.images.sld[1]-1));
			jscolor.picker.sldM.style.backgroundPosition =
				'0 ' + (THIS.pickerFace+THIS.pickerInset+y - Math.floor(jscolor.images.arrow[1]/2)) + 'px';
		}
		
		function redrawOpacity() {
			THIS.opacity = jscolor.HEX.O(valueElement.value);
			jscolor.picker.sldO.style.backgroundPosition = '0 '+(((1-THIS.opacity)*100)+10)+'px';
		}

		function isPickerOwner() {
			return jscolor.picker && jscolor.picker.owner === THIS;
		}

		function blurTarget() {
			if(valueElement === target) {
				THIS.importColor();
			}
			if( valueElement.value == '' ){
				valueElement.style.background = 'none';
			}
			if(THIS.pickerOnfocus) {
				THIS.hidePicker();
			}
		}

		function blurValue() {
			if(valueElement !== target) {
				THIS.importColor();
			}
		}

		function setPad(e) {
			var mpos = jscolor.getRelMousePos(e);
			var x = mpos.x - THIS.pickerFace - THIS.pickerInset;
			var y = mpos.y - THIS.pickerFace - THIS.pickerInset;
			switch(modeID) {
				case 0: THIS.fromHSV(x*(6/(jscolor.images.pad[0]-1)), 1 - y/(jscolor.images.pad[1]-1), null, leaveSld); break;
				case 1: THIS.fromHSV(x*(6/(jscolor.images.pad[0]-1)), null, 1 - y/(jscolor.images.pad[1]-1), leaveSld); break;
			}
			
			var color = THIS.toString();

		}

		function setSld(e) {
			var mpos = jscolor.getRelMousePos(e);
			var y = mpos.y - THIS.pickerFace - THIS.pickerInset;
			switch(modeID) {
				case 0: THIS.fromHSV(null, null, 1 - y/(jscolor.images.sld[1]-1), leavePad); break;
				case 1: THIS.fromHSV(null, 1 - y/(jscolor.images.sld[1]-1), null, leavePad); break;
			}
			
			y += 10;
			if(y < 10)y = 10;
			else if(y > 110)y= 110;
			jscolor.picker.sldM.style.backgroundPosition = '0 '+y+'px';
		}

		function setOpacity(e) {
			
			var mpos = jscolor.getRelMousePos(e);
			var y = (mpos.y - THIS.pickerFace - THIS.pickerInset)+10;
			if(y < 10)y = 10;
			else if(y > 110)y= 110;
			
			// Move arrow
			jscolor.picker.sldO.style.backgroundPosition = '0 '+y+'px';
			
			// Caculate opacity
			
			y = 1-((y-10)/100);
			
			if( y !== 0 && y !== 1 )
				y = y.toString()+'0000';
			
			THIS.opacity = y.toString().substr(0,4).trim();
			
			THIS.exportColor();
			
		}

		function dispatchImmediateChange() {
			if (THIS.onImmediateChange) {
				if (typeof THIS.onImmediateChange === 'string') {
					eval(THIS.onImmediateChange);
				} else {
					THIS.onImmediateChange(THIS);
				}
			}
		}

		var THIS = this;
		var modeID = this.pickerMode.toLowerCase()==='hvs' ? 1 : 0;
		var abortBlur = false;
					
		var
			valueElement = jscolor.fetchElement(this.valueElement),
			styleElement = jscolor.fetchElement(this.styleElement);
		var
			holdPad = false,
			holdSld = false,
			holdSldO = false;
		var
			leaveValue = 1<<0,
			leaveStyle = 1<<1,
			leavePad = 1<<2,
			leaveSld = 1<<3;

		// target
		jscolor.addEvent(target, 'focus', function() {
			if( THIS.pickerOnfocus && !document.getElementsByClassName('kc_color_picker')[0] ) { THIS.showPicker(); }
		});		
		jscolor.addEvent(target, 'mousedown', function() {
			if( THIS.pickerOnfocus && !document.getElementsByClassName('kc_color_picker')[0] ) { THIS.showPicker(); }
		});
		jscolor.addEvent(target, 'blur', function() {
			if(!abortBlur) {
				window.setTimeout(function(){ abortBlur || blurTarget(); abortBlur=false; }, 0);
			} else {
				abortBlur = false;
			}
		});

		// valueElement
		if(valueElement) {
			var updateField = function() {
				if(valueElement.value.length < 6 && jscolor.name2Hex(valueElement.value) === false)
					return;
				THIS.fromString(valueElement.value, leaveValue);
				dispatchImmediateChange();
			};
			jscolor.addEvent(valueElement, 'keyup', updateField);
			jscolor.addEvent(valueElement, 'input', updateField);
			jscolor.addEvent(valueElement, 'blur', blurValue);
			valueElement.setAttribute('autocomplete', 'off');
		}

		// styleElement
		if(styleElement) {
			styleElement.jscStyle = {
				backgroundImage : styleElement.style.backgroundImage,
				backgroundColor : styleElement.style.backgroundColor,
				color : styleElement.style.color
			};
		}

		// require images
		switch(modeID) {
			case 0: jscolor.requireImage('hs.png'); break;
			case 1: jscolor.requireImage('hv.png'); break;
		}
		jscolor.requireImage('cross.gif');
		jscolor.requireImage('arrow.gif');
		jscolor.requireImage('opa.gif');

		this.importColor();
	}

};


jscolor.install();