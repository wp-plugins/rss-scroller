var rss_scr_current=0
var rss_scr_clipwidth=0

function rss_scr_changeticker(){
rss_scr_crosstick.style.clip="rect(0px 0px auto 0px)"
rss_scr_crosstick.innerHTML=rss_scr_contents[rss_scr_current]
rss_scr_highlight()
}

function rss_scr_highlight(){
var rss_scr_msgwidth=rss_scr_crosstick.offsetWidth
if (rss_scr_clipwidth<rss_scr_msgwidth){
rss_scr_clipwidth+=rss_scr_speed
rss_scr_crosstick.style.clip="rect(0px "+rss_scr_clipwidth+"px auto 0px)"
rss_scr_begin=setTimeout("rss_scr_highlight()",20)
}
else{
rss_scr_clipwidth=0
clearTimeout(rss_scr_begin)
if (rss_scr_current==rss_scr_contents.length-1) rss_scr_current=0
else rss_scr_current++
setTimeout("rss_scr_changeticker()",rss_scr_delay)
}
}

function rss_scr_start(){
rss_scr_crosstick=document.getElementById? document.getElementById("rss_scr_spancontant") : document.all.rss_scr_spancontant
rss_scr_crosstickParent=rss_scr_crosstick.parentNode? rss_scr_crosstick.parentNode : rss_scr_crosstick.parentElement
if (parseInt(rss_scr_crosstick.offsetHeight)>0)
rss_scr_crosstickParent.style.height=rss_scr_crosstick.offsetHeight+'px'
else
setTimeout("rss_scr_crosstickParent.style.height=rss_scr_crosstick.offsetHeight+'px'",100) //delay for Mozilla's sake
rss_scr_changeticker()
}