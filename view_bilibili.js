var queryBtn = document.querySelector(".search");
var result_container = document.querySelector(".result_container");
result_container.style.display = "none";

queryBtn.addEventListener("click", function() {
    var url = document.querySelector("#url").value;
    if (url == '') {
        showTips("请输入地址");
        return
    }
    var cover_img = document.querySelector(".cover_img");
    var video_info_title = document.querySelector(".video_info_title");
    var video_info_desc = document.querySelector(".video_info_desc");
    var table_body = document.querySelector(".table_body");
    result_container.style.display = "none";

    //获取ip信息
    $.get({
        url: 'bilibili.php?url=' + url,
        success: function(data) {
            //看下有多少条数据
            if (data != null && data.code == 1) {
                
                //清空子元素
                while (table_body.hasChildNodes()) {
                    table_body.removeChild(table_body.firstChild);
                }

                result_container.style.display = "block";
                cover_img.src = data.imgurl;
                video_info_title.innerHTML ="视频标题：" + data.title;
                video_info_desc.innerHTML ="视频简介：" + data.desc;

                //组装标题
                var titleTr = document.createElement("tr")
                titleTr.classList.add("table_title")
                var ttd0 = document.createElement("td")
                var ttd1 = document.createElement("td")
                var ttd2 = document.createElement("td")
                var ttd3 = document.createElement("td")
                ttd0.innerHTML = "视频分段名称"
                ttd1.innerHTML = "视频时长"
                ttd2.innerHTML = "清晰度"
                ttd3.innerHTML = "操作"
                titleTr.appendChild(ttd0);
                titleTr.appendChild(ttd1);
                titleTr.appendChild(ttd2);
                titleTr.appendChild(ttd3);
                table_body.appendChild(titleTr);

                data.data.forEach(item => {
                    var tr = document.createElement("tr")
                    tr.classList.add("table_content")
                    var td0 = document.createElement("td")
                    var td1 = document.createElement("td")
                    var td2 = document.createElement("td")
                    var td3 = document.createElement("td")
                    td0.innerHTML = item.title
                    td1.innerHTML = item.durationFormat
                    td2.innerHTML = item.accept
                    var span = document.createElement("span")
                    span.innerHTML = "下载视频"
                    span.classList.add("download");
                    span.addEventListener("click", function() {
                        window.open(item.video_url);             
                    });
                    td3.appendChild(span);
                    tr.appendChild(td0);
                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    table_body.appendChild(tr);
                })
            } else {
                showTips(data.msg)
            }
        },
        error:function(err){
            console.log(err);
        }
    })
})