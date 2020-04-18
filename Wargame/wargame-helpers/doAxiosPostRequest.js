import {playAudio, playAudioBuzz, playAudioLow} from "@markarian/wargame-helpers";
export function doPostRequest(id, event, extraData = false){
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});
    $("#" + id + "").addClass("pushed");
    id = "" + id;
    event = "" + event;
    let data = {id: id, wargame: wargame, event: event};
    if(extraData){
        data = {...data, ...extraData};
    }

    let obj;
    axios.post(pokeUrl, data).then((response) => {
      let success = false;
        try {
            success = response.data.success;
        } catch (e) {
//            alert(data);
            }
        if (success) {
            playAudioLow();
        } else {
            playAudioBuzz();
            var msg = '<span>g</span>';
            $("#comlink").html(msg);
            $("#comlinkWrapper").css({background: 'lightgreen'})
        }
        $('body').css({cursor: "auto"});
        $(this).css({cursor: "auto"});
        $("#" + id + "").removeClass("pushed");
    }).catch(error => {
        playAudioBuzz();
        $('body').css({cursor: "auto"});
        $(this).css({cursor: "auto"});
        $("#" + id + "").removeClass("pushed");
        $("#comlink").html('Working');
    });
//     $.ajax({
//         url: pokeUrl,
//         type: "POST",
//         data: data,
//         error: function (data, text, third) {
//             try {
//                 obj = jQuery.parseJSON(data.responseText);
//             } catch (e) {
// //                alert(data);
//             }
//             if (obj.emsg) {
//                 alert(obj.emsg);
//             }
//             playAudioBuzz();
//             $('body').css({cursor: "auto"});
//             $(this).css({cursor: "auto"});
//             $("#" + id + "").removeClass("pushed");
//             $("#comlink").html('Working');
//         },
//         success: function (data, textstatus) {
//             try {
//                 var success = data.success;
//             } catch (e) {
// //            alert(data);
//             }
//             if (success) {
//                 playAudioLow();
//
//             } else {
//                 playAudioBuzz();
//
//                 var msg = '<span>g</span>';
//                 $("#comlink").html(msg);
//                 $("#comlinkWrapper").css({background: 'lightgreen'})
//             }
//             $('body').css({cursor: "auto"});
//             $(this).css({cursor: "auto"});
//             $("#" + id + "").removeClass("pushed");
//
//
//         }
//     });
    $("#mychat").attr("value", "");
}
