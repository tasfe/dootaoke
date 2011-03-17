var imageType = Class({},{
	/*input,upload,select*/
	Create	: function (ul) {
		this.list = [];
		var list = getObj(ul).getElementsByTagName('span');
		for (var i=0;i<list.length;i++) {
			this.list[i] = list[i];
			if (list[i].id == 'imagetype_select') 
				this.select = getObj('imagetype_select_div');
		}
		this._init();
	},
	
	_init	: function () {
		var _this = this;
		
		for (var type in this.list) {
			if (type == 'event') continue;
			this.list[type].onclick = function () {
				for (var i in _this.list) {
					if (i == 'event') continue;
					getObj(_this.list[i].id+'_div').style.display = 'none';
				}
				getObj(this.id+'_div').style.display = '';
			};
		}
		this._initSelect();
	},
	_initSelect	: function () {
		if (this.select) {
			var images = this.select.getElementsByTagName('li');
			for (var i=0;i<images.length;i++) {
				images[i].onclick = function () {
					for (var j in images) {
						if (j == 'event') continue;
						images[j].className = '';
					}
					this.className = 'current';
					var image = this.getElementsByTagName('img');
					getObj('image').value = image[0].src;
				};
			}
		}
	}
});