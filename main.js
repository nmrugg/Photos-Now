window.onload = load_page;

recent_hash = "";

function load_page()
{
	check_hash();
}


function check_hash()
{
	if (recent_hash != window.location.hash) {
		recent_hash = window.location.hash;
		if (recent_hash.length > 1) {
			eval(recent_hash.substr(1));
		}
	}
	setTimeout(check_hash, 40);
}


function show(img_src)
{
	new_img = document.createElement("img");
	new_img.style.visibility = "hidden";
	new_img.src = img_src;
	new_img.className = "photo";
	new_img.title = "Click to close";
	new_img.onclick = function()
	{
		document.body.removeChild(this);
		window.location.hash = "";
	};
	
	new_img.onload = function()
	{
		this.style.left = ((get_innerWidth(window, document) / 2) - (parseInt(this.width) / 2)) + "px";
		this.style.visibility = "visible";
	};
	new_img.style.MozTransform = "rotate(" + (Math.random() * 11 - 5) + "deg)";
	new_img.style.WebkitTransform = new_img.style.MozTransform;
	document.body.appendChild(new_img);
}


function get_innerWidth(win, doc) {
	if (typeof win.innerWidth != "undefined") {
		return win.innerWidth;
	} else {
		return doc.body.clientWidth;
	}
}
