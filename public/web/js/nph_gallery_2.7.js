(function(window, undefined){
		  
	if (window.nph){
		return; 
	}
	
	var $ = window.NTES,
        nph = window.nph = {};
		
	function addStylesheetRules (rules) {   
		var style = document.createElement('style');   
		$.one('body').appendChild(style);   
		if (!window.createPopup) { /* For Safari */  
		   style.appendChild(document.createTextNode(''));   
		}
		var s = document.styleSheets[document.styleSheets.length - 1];   
		for (var selector in rules) {   
			if (s.insertRule) {   
				s.insertRule(selector + '{' + rules[selector] + '}', s.cssRules.length);   
			}   
			else { /* IE */  
				s.addRule(selector, rules[selector], -1);   
			}   
		}   
	}  
		
	var photoViewMode = {
		0: function(index){
			var t = this;
			return {
				prev: index > 0 ? '#p=' + t.photoInfo[index - 1].id : '#p=' + t.photoInfo[t.size - 1].id,
				next: index < t.size - 1 ? '#p=' + t.photoInfo[index + 1].id : '#p=' + t.photoInfo[0].id
			};
		},
		1: function(index){
			var t = this;
			return {
				prev: index > 0 ? '#p=' + t.photoInfo[index - 1].id : t.$prevSet.href,
				next: index < t.size - 1 ? '#p=' + t.photoInfo[index + 1].id : t.$nextSet.href
			};
		}
	};
		
	function Gallery(option){ 
		var t = this,
			sn = option.sn || '',
			ids = ['gallery', 'galleryTpl', 'galleryRelat', 'modePhoto', 'modeStream', 'modeSearch', 'setInfo', 'photoType', 'streamType', 'viewOrig', 'viewStream', 'viewPhoto', 'scrl', 'bar', 'thumb', 'scrlPrev', 'scrlNext', 'photo', 'photoIndex', 'photoDesc', 'photoView', 'photoPrev', 'photoNext', 'photoLoading', 'stream', 'search' ,'searchInput', 'searchPhoto', 'searchTheme', 'prevSet', 'nextSet', 'photoList'],
			i = ids.length;
		
		t.turn = photoViewMode[isNaN(option.photoViewMode) ? 0 : option.photoViewMode];
		t.isChangeUrl = sn ? false : true;
		
		while(--i >= 0){
			t['$' + ids[i]] = $('#' + ids[i] + sn);
		}
		
		t.photoIndex = {};
		t.photoInfo = [];
		t.streamSize = {};
		
		t.$thumb.attr('innerHTML', t.$photoList.value);
		t.$thumbs = t.$thumb.$('> li');
		t.size = t.$thumbs.length;
		
		var pWidth = t.$photoView.offsetWidth,
			pHeight = t.$photoView.offsetHeight;
			uWidth = 106,
			tWidth = uWidth * t.size,
			tCntWidth = t.$thumb.parentNode.offsetWidth,
			tNum = Math.floor(tCntWidth / uWidth);
		
		tCntWidth = uWidth * tNum;
		
		var bCntWidth = t.$bar.parentNode.offsetWidth,
			bWidth = Math.max(36, Math.min(tCntWidth * bCntWidth / tWidth, bCntWidth));
		
		t.$photoView.addCss({ width: pWidth + 'px' });
		t.$scrl.addCss({ width: (tCntWidth + 60) + 'px' });
		t.$thumb.addCss({width: tWidth + 'px'});
		t.$bar.addCss({width: bWidth + 'px'});
		
		t.$thumbs.each(function(i) {
			var self = this, mult = Math.max(0, Math.min(i - Math.floor((tNum + 1) / 2) + 1, t.size - tNum));
			t.photoIndex[self.id] = i;
			t.photoInfo.push({
				id: self.id,
				title: $.one('> h2', self).attr('innerHTML'),
				desc: $.one('> p', self).attr('innerHTML'),
				img: $.one('> i[title=img]', self).attr('innerHTML'),
				timg: $.one('> i[title=timg]', self).attr('innerHTML'),
				aimg: $.one('> i[title=aimg]', self) ? $.one('> i[title=aimg]', self).attr('innerHTML') : '',
				pos: uWidth * mult
			});
		}); 
		
		t.bar = new $.ui.Scroll(t.$thumb, 'x', t.$bar);
		
		$([t.$scrlPrev, t.$scrlNext, t.$bar]).addEvent('click', function(event){ event.preventDefault(); });
		t.$scrlPrev.addEvent('mousedown', t.bar.start.bind(t.bar, 'forward')).addEvent('mouseup,mouseout', t.bar.stop.bind(t.bar));
		t.$scrlNext.addEvent('mousedown', t.bar.start.bind(t.bar, 'backward')).addEvent('mouseup,mouseout', t.bar.stop.bind(t.bar));
		
		$([t.$photoPrev, t.$photoNext].concat(t.$thumbs.$('> a'))).addEvent('click', function(event){
			var index = t.getIndex(this.href);
			if(index !== undefined){
				event.preventDefault();	
				t.showPhoto(index);
				t.stats();
			}
		});
				
		t.$photo.addEvent('load', function(){
			'string' !== typeof this.style.maxWidth && t.resize(this, { width: pWidth, height: pHeight });
			t.$photoLoading.addCss('hidden');
			var index = t.getIndex(t.$photoNext.href);
			if(index !== undefined){
				(new Image).src = t.photoInfo[index].img;
			}
		});
		
		if(t.$searchPhoto || t.$viewStream){
			var sHeight = pHeight + 130,
				rules = {};
		
			t.streamSize.cols = Math.floor(pWidth / 180);
			t.streamSize.rows = Math.floor(sHeight / 215);
			t.streamSize.size = t.streamSize.cols * t.streamSize.rows;
		
			rules[String.format('#gallery%1 .nph_list_stream', sn)] = String.format('width:%1px;height:%2px;', pWidth, sHeight);
			rules[String.format('#gallery%1 .nph_list_stream li', sn)] = String.format('width:%1px;height:%2px;', Math.floor(pWidth / t.streamSize.cols), Math.floor(sHeight / t.streamSize.rows));
			addStylesheetRules(rules);
			
			$.ui.Template.load(t.$galleryTpl.$('> div'));
			
			t.$viewStream && t.$viewStream.addEvent('click', function(event){ event.preventDefault(); t.changeMode('stream').stats(); });
			
			if(t.$searchPhoto){
				var $label = $.one('> label', t.$searchInput.parentNode);
				t.$searchInput.addEvent('focus', function(){  $label.addCss('hidden'); }).addEvent('blur', function(){ !this.value && $label.removeCss('hidden'); });
				t.$searchPhoto.addEvent('submit', function(e){
					e.preventDefault();
					var q = this['q'].value;
					q && t.changeMode('search', q).stats();
				});
				t.$viewPhoto.addEvent('click', function(event){ event.preventDefault(); t.changeMode('photo').stats(); });
			}
		}
		
		var hash = window.location.hash;
		t.$searchPhoto && t.getS(hash) ? t.changeMode('search', t.getS(hash)) : t.$viewStream && t.getQ(hash) ? t.changeMode('stream', t.getQ(hash)) : t.changeMode('photo', t.getIndex(hash));
	};
	
	Gallery.prototype = {
		showPhoto: function(index){
			index = isNaN(index) ? 0 : index;
			var t = this, info = t.photoInfo[index], turn = t.turn(index);
			if(info.img != t.$photo.src){
				t.$photoLoading.removeCss('hidden');
				t.$photoIndex.attr('innerHTML', index + 1);
				t.$photo.src = info.aimg ? info.aimg : info.img;
				t.$photoDesc.attr('innerHTML', String.format('<h2>%1</h2>', info.title) + (info.desc && String.format('<p>%1</p>', info.desc)));
				t.$photoPrev.href = turn.prev;
				t.$photoNext.href = turn.next;
				t.bar.onStart = function(){
					t.$thumbs.removeCss('active');
					t.$thumbs.$(index).addCss('active');
				}; 
				t.bar.scrollTo(info.pos);
				t.$viewOrig && (t.$viewOrig.href = info.img);
				t.isChangeUrl && t.changeUrl('#p=' + info.id);
			}
		},
		showStream: function(page){
			var t = this;
			page = isNaN(page) || page < 1 || page > t.size ? 1 : page;
			var params = {
					stream: t.photoInfo.slice(t.streamSize.size * (page - 1), t.streamSize.size * page),
					page: page,
					total: Math.ceil(t.size / t.streamSize.size)
				};
			t.$stream.attr('innerHTML', $.ui.Template.parse('stream', params));
			t.$stream.$('> ul.nph_list_stream a').addEvent('click', function(event){ event.preventDefault(); t.changeMode('photo', t.getIndex(this.href)).stats(); });
			t.$stream.$('> div.nph_pages > a').addEvent('click', function(event){ event.preventDefault(); t.showStream(t.getQ(this.href)); });
			t.isChangeUrl && t.changeUrl('#q=' + page);
		},
		showSearch: function(s, page){
			s = s || '';
			page = isNaN(page) || page < 1 ? 1 : page;
			var t = this,
				channelid = t.channelid || '',
				size = t.streamSize.size,
				start = (page - 1) * size;
			$.ajax.importJs(
				String.format('', s, channelid, size, start),
				t.buildSearch.bind(t)
			);
			t.isChangeUrl && t.changeUrl('#s=' + s + '&q=' + page);
		},
		buildSearch: function(){
			var t = this, hs = t.$searchTheme.$('> h3'), $found = hs.$(0), $notfound = hs.$(1);
			if(!jsonres.availHits){
				$found.addCss('hidden');
				$notfound.removeCss('hidden');
				$.one('> span', $notfound).attr('innerHTML', String.format('"%1"', jsonres.q));
				t.$search.attr('innerHTML', '');
				return false;
			}
			$found.removeCss('hidden');
			$notfound.addCss('hidden');
			$.one('> span', $found).attr('innerHTML', String.format('"%1"', jsonres.q));
			$.one('> span.nph_search_count span', $found).attr('innerHTML', jsonres.availHits);
			var params = {
				q: jsonres.q,
				stream: jsonres.hits,
				page: Math.floor(jsonres.start / jsonres.length) + 1,
				total: Math.ceil(jsonres.availHits / jsonres.length)
			}
			
			t.$search.attr('innerHTML', $.ui.Template.parse('search', params));
			
			t.$search.$('> div.nph_pages > a').addEvent('click', function(event){ event.preventDefault(); t.showSearch(t.getS(this.href), t.getQ(this.href)); });
		},
		changeMode: function(mode, params){
			var	t = this;
			switch(mode){
				case 'photo':
					$([t.$modePhoto, t.$photoType, t.$setInfo, t.$galleryRelat]).removeCss('hidden');
					$([t.$modeStream, t.$streamType, t.$modeSearch, t.$searchTheme]).addCss('hidden');
					t.showPhoto(params);
					break;
				case 'stream':
					$([t.$modeStream, t.$streamType, t.$setInfo, t.$galleryRelat]).removeCss('hidden');
					$([t.$modePhoto, t.$photoType, t.$modeSearch, t.$searchTheme]).addCss('hidden');
					t.showStream(params);
					break;
				case 'search':
					$([t.$modeSearch, t.$searchTheme]).removeCss('hidden');
					$([t.$modePhoto, t.$modeStream, t.$setInfo, t.$galleryRelat]).addCss('hidden');
					t.showSearch(params);
					break;
			}
			return t;
		},
		resize: function($img, size){
			$img.removeAttribute('width');
      		$img.removeAttribute('height');
			size = size || {};
			var rw = size.width ? $img.width / size.width : 0,
				rh = size.height ? $img.height / size.height : 0;
			if(rw > 1 || rh > 1){
				rw > rh ? $img.width = size.width : $img.height = size.height;
			}
		},
		getIndex: function(url){
			var t = this,
				matchs = url.match(/#([^&]*&)*p=([^&]+)/i),
				id = matchs ? matchs[2] : undefined;
			return t.photoIndex[id];
		},
		getQ: function(url){
			var matchs = url.match(/#([^&]*&)*q=([^&]+)/i),
				q = matchs ? parseInt(matchs[2]) : NaN;
			return q;
		},
		getS: function(url){
			var matchs = url.match(/#([^&]*&)*s=([^&]+)/i),
				s = matchs ? matchs[2] : undefined;
			return s;
		},
		changeUrl: function (url) {
        	window.location.href = url;
    	},
		stats: function(){
			'function' === typeof vjEventTrack && vjEventTrack();
        	'function' === typeof neteaseTracker && neteaseTracker();
		}
	}

	nph.Gallery = Gallery;
})(window);