<script type="text/javascript">
x.register("victory", function(vp, data){
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

x.register("vp", function(vp, data){
    });

    DR.$(document).ready(function(){
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