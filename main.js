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


function fade_obj(obj, opacity, step, speed, run_at_end, test)
{
    opacity += step;
    obj.style.opacity = opacity / 100;

    if (test(opacity)) {
        run_at_end();
    } else {
        setTimeout(function ()
        {
            fade_obj(obj, opacity, step, speed, run_at_end, test);
        }, speed);
    }
}


function show(img_src)
{
    new_img = document.createElement("img");
    new_img.style.visibility = "hidden";
    new_img.src = img_src;
    new_img.className = "photo";
    new_img.title = "Click to close; right click to save";
    new_img.onclick = function()
    {
        set_up_fade(new_img, 100, 0, -15, 30, function ()
        {
            window.location.hash = "//";
            document.body.removeChild(new_img);
        });
    };
    
    new_img.onload = function()
    {
        this.style.left = ((get_innerWidth(window, document) / 2) - (parseInt(this.width) / 2)) + "px";
        
        this.style.opacity = 0;
        
        set_up_fade(new_img, 0, 100, 15, 30, function () {});
        
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


function set_up_fade(obj, start_op, stop_op, step, speed, run_at_end)
{
        setTimeout(function ()
        {
            fade_obj(obj, start_op, step, speed, run_at_end, function (cur_opacity)
            {
                if (step < 0) {
                    return cur_opacity <= stop_op;
                } else {
                    return cur_opacity >= stop_op;
                }
            });
        }, speed);
}