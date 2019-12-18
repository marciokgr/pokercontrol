function ajax()
{ 
	this.queue = [];
	this.requested = [];
	this.saveRequests = true;
	this.currentRequest = false;
	this.debug = false;
	
	this.createXHR = function()
	{
		var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
		for(var i=0; i<prefixes.length; i++)
		{
			try
			{
				return new ActiveXObject(prefixes[i] + ".XmlHttp");
			}
			catch(e)
			{
				continue;
			}
		}
		try
		{
			return new XMLHttpRequest();
		}
		catch(e)
		{
			return false;
		}
	};

	this.XHR = this.createXHR();

	this.request = function(strUrl, fnReturn, arrData, isUrlEscaped, isReturnEscaped, fnStart, fnFinish, strMethod, requestHeaders)
	{
		this.aborted = 0;
		this.strUrl = strUrl;
		this.fnReturn = fnReturn;
		this.isReturnEscaped = (isReturnEscaped == 1) ? 1 : 0;
		this.isUrlEscaped = (isUrlEscaped == 0) ? 0 : 1;
		this.strData = ajax.getDados(arrData, this.isUrlEscaped);
		this.method = (typeof strMethod == "undefined" || strMethod == "" || strMethod == 0) ? ((this.strData == null) ? "GET" : "POST") : strMethod;
		this.readyState = false;
		this.status = false;
		this.requestHeaders = (requestHeaders instanceof Array) ? requestHeaders : [];
		this.responseHeaders = "";
		this.responseText = "";
		this.startLoading = (typeof fnStart != "function") ? ajax.startLoading : fnStart;
		this.finishLoading = (typeof fnFinish != "function") ? ajax.finishLoading : fnFinish;
				
		if(this.method == "POST")
		{
			this.requestHeaders.push("Content-type=application/x-www-form-urlencoded");
			this.requestHeaders.push("encoding=ISO-8859-1");
		}

		return this;

	};

	this.abort = function()
	{
		this.currentRequest.aborted = 1;
		return this.XHR.abort();
	};

	
	this.addRequest = function(strUrl, fnReturn, arrData, isUrlEscaped, isReturnEscaped, fnStart, fnFinish, strMethod, requestHeaders)
	{
		if(arguments.length == 1) this.queue.push(arguments[0]);
		else this.queue.push(new this.request(strUrl, fnReturn, arrData, isUrlEscaped, isReturnEscaped, fnStart, fnFinish, strMethod, requestHeaders));
		if(this.queue.length == 1 && !this.currentRequest) this.doRequest();
	};

	this.doRequest = function()
	{
		if(this.currentRequest = this.queue.pop())
		{
			try
			{
				this.currentRequest.startLoading();
				this.XHR.open(this.currentRequest.method,this.currentRequest.strUrl+(this.currentRequest.strUrl.indexOf("?")==-1?"?":"&")+Math.random(),true);
				this.XHR.onreadystatechange=function() { var a=this; return function () { a.doReturn.apply(a, [a.XHR]); }}.apply(this);
			
				if(this.currentRequest.requestHeaders.length > 0)
					for(var i = 0, arrHeader; i<this.currentRequest.requestHeaders.length && (arrHeader = this.currentRequest.requestHeaders[i].split("=")); i++)
						this.XHR.setRequestHeader(arrHeader[0],arrHeader[1]);
				this.XHR.send(this.currentRequest.strData);
			}
			catch(error)
			{
				this.currentRequest.finishLoading(false);
				if(this.debug) alert(this.debugRequest(this.currentRequest));
			}
		}
	};
	
	this.doReturn = function()
	{
		this.currentRequest.readyState = this.XHR.readyState;
		
		if(this.currentRequest.readyState==4)
		{
			this.currentRequest.finishLoading();
			
			if(!this.currentRequest.aborted)
			{
				this.currentRequest.status = this.XHR.status;
				this.currentRequest.responseText = this.XHR.responseText;
				this.currentRequest.responseHeaders = this.XHR.getAllResponseHeaders();
				if(this.currentRequest.isReturnEscaped) this.currentRequest.responseText = unescape(this.currentRequest.responseText.replace(/\+/g," "));
				
				this.currentRequest.fnReturn(this.currentRequest.responseText, this.currentRequest);
			}
			
			if(this.saveRequests) this.requested.push(this.currentRequest);
			this.currentRequest = false;
			if(this.queue.length>0)this.doRequest();
		}
	};


	this.getDados = function(obj, isUrlEscaped)
	{
		var s = "";
		var i;
		
		if(obj == null || obj.length == 0) return null;
		if(obj instanceof Array)
		{
			for(i=0; i<obj.length; i++)
			{
				if(i>0) s += "&";
				s += this.getDados(obj[i], isUrlEscaped);
			}
			return s;
		}
		if(typeof(obj) == "string")
		{
			return obj;
		}
		
		if(obj.nodeName.toLowerCase() == "form")
		{
			var is, ss, ts;
			var v = new Array();
			
			is = $tags('input',obj);
			ss = $tags('select',obj);
			ts = $tags('textarea',obj);
			
			for(i=0; i<ts.length; i++)
			{
				if(!ts[i].disabled) v.push([ts[i].name,ts[i].value]);
			}
			
			for(i=0; i<is.length; i++)
			{
				if(is[i].type == "text" && !is[i].disabled) v.push([is[i].name,is[i].value]);
				if(is[i].type == "password" && !is[i].disabled) v.push([is[i].name,is[i].value]);
				if(is[i].type == "hidden" && !is[i].disabled) v.push([is[i].name,is[i].value]);
				if((is[i].type == "radio" || is[i].type == "checkbox") && is[i].checked  && !is[i].disabled) v.push([is[i].name,is[i].value]); 
			}
			
			for(i=0; i<ss.length; i++)
			{
				if(ss[i].selectedIndex == -1) v.push(ss[i].name,'');
				else v.push(new Array(ss[i].name,ss[i].options[ss[i].selectedIndex].value));
			}
			
			for(i=0; i<v.length; i++)
			{
				if(i>0) s += "&";
				if(isUrlEscaped) s += encodeURIComponent(v[i][0])+"="+encodeURIComponent(v[i][1]);
				else s += v[i][0]+"="+v[i][1];
			}
			
			return s;
		}
		
	};

	this.startLoading = function()
	{
		return true;
	};

	this.finishLoading = function()
	{
		return true;
	};

	this.debugRequest = function(r)
	{
		var strDebug;
		var arrParams = ["aborted","strUrl","fnReturn","strData","method","readyState","status","requestHeaders","responseHeaders","responseText","isReturnEscaped","isUrlEscaped","startLoading","finishLoading"];
		
		if(typeof r == "undefined") r = this.requested[this.requested.length-1];

		if(r) 
		{
			strDebug = "Request Debug";

			for(var i = 0; i<arrParams.length; i++)
			{
				strDebug += "\n\t"+arrParams[i]+": \""+r[arrParams[i]].toString().replace("\n","")+"\"";
			}
		}
		else
		{
			strDebug = false;
		}
		
		return strDebug
	}
}

var ajax = new ajax();

function enviaDadosAJAX(url, fnRetorno, divCarregando, async)
{
	if (fnRetorno == undefined)
	{
		fnRetorno = "RetornoLocal";
	}
	
	eval('fnRetorno=' + fnRetorno);
	
	
	var startLoading = function(){ return true; };
	var finishLoading = function(){ return true; };
	
	
	ajax.addRequest(url, fnRetorno, undefined, undefined, undefined, startLoading, finishLoading, async,"GET");
	
	return;		
}