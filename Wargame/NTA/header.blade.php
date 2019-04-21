<script type="text/javascript">
window.legacy.x.register("victory", function(vp, data){
    var DR = window.legacy.DR;
    $ = DR.$;
    var ownerObj = data.specialHexes;
    var owner;
    for(i in ownerObj){
        owner = ownerObj[i];
        break;
    }
    var name;
    if(owner == 0){
        name = "Nobody Owns the tree";
    }
    if(owner == 1){
        name = "<span class='playerRedFace'>Red owns the tree </span>";
    }
    if(owner == 2){
        name = "<span class='playerBlueFace'>Blue owns the tree </span>";
    }
    $("#victory").html(name);

});

window.legacy.x.register("vp", function(vp, data){
    });
document.addEventListener("DOMContentLoaded",function(){
    var DR = window.legacy.DR;
    $ = DR.$;
     $("#altTable").on('click', function(){
            $(this).hide();
            $("#mainTable").show();
            $('.tableWrapper.main').hide();
            $('.tableWrapper.alt').show();
        });
        $("#mainTable").on('click', function(){
            $(this).hide();
            $("#altTable").show();
            $('.tableWrapper.alt').hide();
            $('.tableWrapper.main').show();
        });
        $("#altTable").show();
        $("#mainTable").hide();
        $(".tableWrapper.alt").hide();
        $(".tableWrapper.main").show();
    });
</script>