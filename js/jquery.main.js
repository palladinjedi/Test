$(function(){
	initPopup();
	initGallery();
	initEqual ();
})
function initEqual () {
	if ($('.stock-holder .left-column').height() < $('.stock-holder .right-column').height()) {
		$('.stock-holder .left-column').css('height',$('.stock-holder .right-column').height())
	} else {
		$('.stock-holder .right-column').css('height',$('.stock-holder .left-column').height())
	}
}
function initGallery(){
	$('.gallery-content').galleryScroll({
		
	});
}
function initPopup(){
	function refresh(){
		$('.form-legend .checkboxAreaChecked').removeClass('checkboxAreaChecked').addClass('checkboxArea')
	}
	$('.left-box .btn-link').click(function(){
		$('.add-legend').fadeIn(300);
		bgResize();
		return false;
	})
	$('.text .btn-link-edit').click(function(){
		$('.edit-legend').fadeIn(300);
		bgResize();
		return false;
	})
	$('.left-box .btn-link-guest').click(function(){
		$('.popup-holder').fadeIn(300);
		bgResize();
		return false;
	})
	$('.paging .btn-link-guest').click(function(){
		$('.popup-holder').fadeIn(300);
		bgResize();
		return false;
	})
	$('.paging .btn-link').click(function(){
		$('.add-legend').fadeIn(300);
		bgResize();
		return false;
	})
	$('.sidebar .btn-link').click(function(){
		$('.add-legend').fadeIn(300);
		bgResize();
		return false;
	})
        $('.sidebar .btn-link-guest').click(function(){
		$('.popup-holder').fadeIn(300);
		bgResize();
		return false;
	})
        $('.form-legend .row-link a').click(function(){
		$(this).parent().find('.active').removeClass('active').end().end().addClass('active');
	})
	$('.bg').click(function(e){
		$(this).parent().fadeOut(300)
		return false;
	})
	$('.form-legend textarea').keyup(function(){
		var val=$(this).val();
		if(val.length>900){
			$(this).val(val.substr(0,900))
			$('.form-legend .info-text').text('Использовано знаков '+$(this).val().length+' из 900');
		}else{
			$('.form-legend .info-text').text('Использовано знаков '+val.length+' из 900');
		}
		
	})
	if(window.location.hash){
		var hash=window.location.hash.replace(/#/g,'');
		switch(hash){
			case 'add':
				$('.add-legend').fadeIn(300);
				bgResize()
			break
			case 'login':
				$('.popup-holder').eq(0).fadeIn(300);
				bgResize()
			break
			case 'success':
				$('.popup-holder').eq(1).fadeIn(300);
				bgResize()
			break
		}
	}
	
	$('.box-message .row .btn01').click(function(){
		$(this).parents('.popup-holder').fadeOut(300);
		return false
	})
	$('.box-message .row .btn02').click(function(){
		$(this).parents('.popup-holder').fadeOut(300);
		$('.popup-holder.add-legend').fadeIn(300);
		return false
	})
	$('.popup').click(function(e){
		return false;
	})
	$('.popup .close').click(function(e){
		$(this).parents('.popup-holder').fadeOut(300);
		return false;
	})
	$(window).resize(bgResize)
	$(window).scroll(bgResize)
	function bgResize(){
			var _w=$(window).width(),
			_h=$(document).height();
			 $('.bg').css({"height":_h,"width":_w+$(window).scrollLeft()});
	}
}

jQuery.fn.galleryScroll = function(_options){
	// defaults options	
	var _options = jQuery.extend({
		btPrev: 'a.prev',
		btNext: 'a.next',
		holderList: '.gallery',
		scrollElParent: 'ul',
		scrollEl: 'li',
		slideNum: false,
		duration : 1000,
		step: false,
		circleSlide: true,
		disableClass: 'disable',
		funcOnclick: null,
		autoSlide: 5000,
		innerMargin:0,
		stepWidth:false
	},_options);

	return this.each(function(){
		var _this = jQuery(this);

		var _holderBlock = jQuery(_options.holderList,_this);
		var _gWidth = _holderBlock.width();
		var _animatedBlock = jQuery(_options.scrollElParent,_holderBlock);
		var _liWidth = jQuery(_options.scrollEl,_animatedBlock).outerWidth(true);
		var _liSum = jQuery(_options.scrollEl,_animatedBlock).length * _liWidth;
		var _margin = -_options.innerMargin;
		var f = 0;
		var _step = 0;
		var _autoSlide = _options.autoSlide;
		var _timerSlide = null;
		if (!_options.step) _step = _gWidth; else _step = _options.step*_liWidth;
		if (_options.stepWidth) _step = _options.stepWidth;
		
		if (!_options.circleSlide) {
			if (_options.innerMargin == _margin)
				jQuery(_options.btPrev,_this).addClass('prev-'+_options.disableClass);
		}
		if (_options.slideNum && !_options.step) {
			var _lastSection = 0;
			var _sectionWidth = 0;
			while(_sectionWidth < _liSum)
			{
				_sectionWidth = _sectionWidth + _gWidth;
				if(_sectionWidth > _liSum) {
				       _lastSection = _sectionWidth - _liSum;
				}
			}
		}
		if (_autoSlide) {
				_timerSlide = setTimeout(function(){
					autoSlide(_autoSlide);
				}, _autoSlide);
			_animatedBlock.hover(function(){
				clearTimeout(_timerSlide);
			}, function(){
				_timerSlide = setTimeout(function(){
					autoSlide(_autoSlide)
				}, _autoSlide);
			});
		}
	
		// click button 'Next'
		jQuery(_options.btNext,_this).bind('click',function(){
			jQuery(_options.btPrev,_this).removeClass('prev-'+_options.disableClass);
			if (!_options.circleSlide) {
				if (_margin + _step  > _liSum - _gWidth - _options.innerMargin) {
					if (_margin != _liSum - _gWidth - _options.innerMargin) {
						_margin = _liSum - _gWidth  + _options.innerMargin;
						jQuery(_options.btNext,_this).addClass('next-'+_options.disableClass);
						_f2 = 0;
					} 
				} else {
					_margin = _margin + _step;
					if (_margin == _liSum - _gWidth - _options.innerMargin) {
						jQuery(_options.btNext,_this).addClass('next-'+_options.disableClass);_f2 = 0;
					} 					
				}
			} else {
				if (_margin + _step  > _liSum - _gWidth + _options.innerMargin) {
					if (_margin != _liSum - _gWidth + _options.innerMargin) {
						_margin = _liSum - _gWidth  + _options.innerMargin;
					} else {
						_f2 = 1;
						_margin = -_options.innerMargin;
					}
				} else {
					_margin = _margin + _step;
					_f2 = 0;
				}
			} 
			
			_animatedBlock.animate({marginLeft: -_margin+"px"}, {queue:false,duration: _options.duration });
			
			if (_timerSlide) {
				clearTimeout(_timerSlide);
				_timerSlide = setTimeout(function(){
					autoSlide(_options.autoSlide)
				}, _options.autoSlide);
			}
			
			if (_options.slideNum && !_options.step) jQuery.fn.galleryScroll.numListActive(_margin,jQuery(_options.slideNum, _this),_gWidth,_lastSection);		
			if (jQuery.isFunction(_options.funcOnclick)) {
				_options.funcOnclick.apply(_this);
			}
			return false;
		});
		// click button 'Prev'
		var _f2 = 1;
		jQuery(_options.btPrev, _this).bind('click',function(){
			jQuery(_options.btNext,_this).removeClass('next-'+_options.disableClass);
			if (_margin - _step >= -_step - _options.innerMargin && _margin - _step <= -_options.innerMargin) {
				if (_f2 != 1) {
					_margin = -_options.innerMargin;
					_f2 = 1;
				} else {
					if (_options.circleSlide) {
						_margin = _liSum - _gWidth  + _options.innerMargin;
						f=1;_f2=0;
					} else {
						_margin = -_options.innerMargin
					}
				}
			} else if (_margin - _step < -_step + _options.innerMargin) {
				_margin = _margin - _step;
				f=0;
			}
			else {_margin = _margin - _step;f=0;};
			
			if (!_options.circleSlide && _margin == _options.innerMargin) {
				jQuery(this).addClass('prev-'+_options.disableClass);
				_f2=0;
			}
			
			if (!_options.circleSlide && _margin == -_options.innerMargin) jQuery(this).addClass('prev-'+_options.disableClass);
			_animatedBlock.animate({marginLeft: -_margin + "px"}, {queue:false, duration: _options.duration});
			
			if (_options.slideNum && !_options.step) jQuery.fn.galleryScroll.numListActive(_margin,jQuery(_options.slideNum, _this),_gWidth,_lastSection);
			
			if (_timerSlide) {
				clearTimeout(_timerSlide);
				_timerSlide = setTimeout(function(){
					autoSlide(_options.autoSlide)
				}, _options.autoSlide);
			}
			
			if (jQuery.isFunction(_options.funcOnclick)) {
				_options.funcOnclick.apply(_this);
			}
			return false;
		});
		
		if (_liSum <= _gWidth) {
			jQuery(_options.btPrev,_this).addClass('prev-'+_options.disableClass).unbind('click');
			jQuery(_options.btNext,_this).addClass('next-'+_options.disableClass).unbind('click');
		}
		// auto slide
		function autoSlide(autoSlideDuration){
			//if (_options.circleSlide) {
				jQuery(_options.btNext,_this).trigger('click');
			//}
		};
		// Number list
		jQuery.fn.galleryScroll.numListCreate = function(_elNumList, _liSumWidth, _width, _section){
			var _numListElC = '';
			var _num = 1;
			var _difference = _liSumWidth + _section;
			while(_difference > 0)
			{
				_numListElC += '<li><a href="">'+_num+'</a></li>';
				_num++;
				_difference = _difference - _width;
			}
			jQuery(_elNumList).html('<ul>'+_numListElC+'</ul>');
		};
		jQuery.fn.galleryScroll.numListActive = function(_marginEl, _slideNum, _width, _section){
			if (_slideNum) {
				jQuery('a',_slideNum).removeClass('active');
				var _activeRange = _width - _section-1;
				var _n = 0;
				if (_marginEl != 0) {
					while (_marginEl > _activeRange) {
						_activeRange = (_n * _width) -_section-1 + _options.innerMargin;
						_n++;
					}
				}
				var _a  = (_activeRange+_section+1 + _options.innerMargin)/_width - 1;
				jQuery('a',_slideNum).eq(_a).addClass('active');
			}
		};
		if (_options.slideNum && !_options.step) {
			jQuery.fn.galleryScroll.numListCreate(jQuery(_options.slideNum, _this), _liSum, _gWidth,_lastSection);
			jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this),_gWidth,_lastSection);
			numClick();
		};
		function numClick() {
			jQuery(_options.slideNum, _this).find('a').click(function(){
				jQuery(_options.btPrev,_this).removeClass('prev-'+_options.disableClass);
				jQuery(_options.btNext,_this).removeClass('next-'+_options.disableClass);
				
				var _indexNum = jQuery(_options.slideNum, _this).find('a').index(jQuery(this));
				_margin = (_step*_indexNum) - _options.innerMargin;
				f=0; _f2=0;
				if (_indexNum == 0) _f2=1;
				if (_margin + _step > _liSum) {
					_margin = _margin - (_margin - _liSum) - _step + _options.innerMargin;
					if (!_options.circleSlide) jQuery(_options.btNext, _this).addClass('next-'+_options.disableClass);
				}
				_animatedBlock.animate({marginLeft: -_margin + "px"}, {queue:false, duration: _options.duration});
				
				if (!_options.circleSlide && _margin==0) jQuery(_options.btPrev,_this).addClass('prev-'+_options.disableClass);
				jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this),_gWidth,_lastSection);
				
				if (_timerSlide) {
					clearTimeout(_timerSlide);
					_timerSlide = setTimeout(function(){
						autoSlide(_options.autoSlide)
					}, _options.autoSlide);
				}
				return false;
			});
		};
		jQuery(window).resize(function(){
			_gWidth = _holderBlock.width();
			_liWidth = jQuery(_options.scrollEl,_animatedBlock).outerWidth(true);
			_liSum = jQuery(_options.scrollEl,_animatedBlock).length * _liWidth;
			if (!_options.step) _step = _gWidth; else _step = _options.step*_liWidth;
			if (_options.slideNum && !_options.step) {
				var _lastSection = 0;
				var _sectionWidth = 0;
				while(_sectionWidth < _liSum)
				{
					_sectionWidth = _sectionWidth + _gWidth;
					if(_sectionWidth > _liSum) {
					       _lastSection = _sectionWidth - _liSum;
					}
				};
				jQuery.fn.galleryScroll.numListCreate(jQuery(_options.slideNum, _this), _liSum, _gWidth,_lastSection);
				jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this),_gWidth,_lastSection);
				numClick();
			};
			//if (_margin == _options.innerMargin) jQuery(this).addClass(_options.disableClass);
			if (_liSum - _gWidth  < _margin - _options.innerMargin) {
				if (!_options.circleSlide) jQuery(_options.btNext, _this).addClass('next-'+_options.disableClass);
				_animatedBlock.animate({marginLeft: -(_liSum - _gWidth + _options.innerMargin)}, {queue:false, duration: _options.duration});
			};
		});
	});
}
