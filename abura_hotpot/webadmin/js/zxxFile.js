/*
 * zxxFile.js 基於HTML5 文件上傳的核心腳本 http://www.zhangxinxu.com/wordpress/?p=1923
 * by zhangxinxu 2011-09-12
*/

var ZXXFILE = {
  rootpath:"",
  webpath:"",
  fileDir:"",
  paramsdata:null,
	fileInput: null,				//html file控件
	dragDrop: null,					//拖拽敏感區域
	upButton: null,					//提交按鈕
	url: "",						//ajax地址
	fileFilter: [],					//過濾後的文件數組
	filter: function(files) {		//選擇文件組的過濾方法
		return files;
	},
	onSelect: function() {},		//文件選擇後
	onDelete: function() {},		//文件刪除後
	onDragOver: function() {},		//文件拖拽到敏感區域時
	onDragLeave: function() {},	//文件離開到敏感區域時
	onProgress: function() {},		//文件上傳進度
	onSuccess: function() {},		//文件上傳成功時
	onFailure: function() {},		//文件上傳失敗時,
	onComplete: function() {},		//文件全部上傳完畢時

	/* 開發參數和內置方法分界線 */

	//文件拖放
	funDragHover: function(e) {
		e.stopPropagation();
		e.preventDefault();
		this[e.type === "dragover"? "onDragOver": "onDragLeave"].call(e.target);
		return this;
	},
	//獲取選擇文件，file控件或拖放
	funGetFiles: function(e) {
		// 取消鼠標經過樣式
		this.funDragHover(e);

		// 獲取文件列表對象
		var files = e.target.files || e.dataTransfer.files;
		//繼續添加文件
		this.fileFilter = this.fileFilter.concat(this.filter(files));
		this.funDealFiles();
		return this;
	},

	//選中文件的處理與回調
	funDealFiles: function() {
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			//增加唯一索引值
			file.index = i;
		}
		//執行選擇回調
		this.onSelect(this.fileFilter);
		return this;
	},

	//刪除對應的文件
	funDeleteFile: function(fileDelete) {
		var arrFile = [];
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			if (file != fileDelete) {
				arrFile.push(file);
			} else {
				this.onDelete(fileDelete);
			}
		}
		this.fileFilter = arrFile;
		return this;
	},

	//文件上傳
	funUploadFile: function() {
		var self = this;
		if (location.host.indexOf("sitepointstatic") >= 0) {
			//非站點服務器上運行
			return;
		}
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			(function(file) {
        var fd = new FormData();
				var xhr = new XMLHttpRequest();
				if (xhr.upload) {
					// 上傳中
					xhr.upload.addEventListener("progress", function(e) {
						self.onProgress(file, e.loaded, e.total);
					}, false);

					// 文件上傳成功或是失敗
					xhr.onreadystatechange = function(e) {
						if (xhr.readyState == 4) {
							if (xhr.status == 200) {
								self.onSuccess(file, xhr.responseText);
								self.funDeleteFile(file);
								if (!self.fileFilter.length) {
									//全部完畢
									self.onComplete();
								}
							} else {
								self.onFailure(file, xhr.responseText);
							}
						}
					};

					// 開始上傳
					xhr.open("POST", self.url, true);
          fd.append('ff[]', file);
          if (self.paramsdata != null){
          	for(var key in self.paramsdata){
          		fd.append(key,self.paramsdata[key]);
          	}
          }
          fd.append('fileDir',self.fileDir);
          fd.append('rootpath',self.rootpath);
          fd.append('webpath',self.webpath);
					xhr.send(fd);
				}
			})(file);
		}

	},

	init: function() {
		var self = this;

		if (this.dragDrop) {
			this.dragDrop.addEventListener("dragover", function(e) { self.funDragHover(e); }, false);
			this.dragDrop.addEventListener("dragleave", function(e) { self.funDragHover(e); }, false);
			this.dragDrop.addEventListener("drop", function(e) { self.funGetFiles(e); }, false);
		}

		//文件選擇控件選擇
		if (this.fileInput) {
			this.fileInput.addEventListener("change", function(e) { self.funGetFiles(e); }, false);
		}

		//上傳按鈕提交
		if (this.upButton) {
			this.upButton.addEventListener("click", function(e) { self.funUploadFile(e); }, false);
		}
	}
};
