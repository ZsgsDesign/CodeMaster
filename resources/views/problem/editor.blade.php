<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>{{$page_title}} | {{$site_title}}</title>
    <!-- Copyright Information -->
    <meta name="author" content="">
    <meta name="organization" content="">
    <meta name="developer" content="">
    <meta name="version" content="">
    <meta name="subversion" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Necessarily Declarations -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="alternate icon" type="image/png" href="/favicon.png">
    <!-- Loading Style -->
    <style>
        loading>div {
            text-align: center;
        }

        loading p {
            font-weight: 300;
        }

        loading {
            display: flex;
            z-index: 999;
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            justify-content: center;
            align-items: center;
            background: #f5f5f5;
            transition: .2s ease-out .0s;
            opacity: 1;
        }

        .lds-ellipsis {
            display: inline-block;
            position: relative;
            width: 64px;
            height: 64px;
        }

        .lds-ellipsis div {
            position: absolute;
            top: 27px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: rgba(0, 0, 0, .54);
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }

        .lds-ellipsis div:nth-child(1) {
            left: 6px;
            animation: lds-ellipsis1 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(2) {
            left: 6px;
            animation: lds-ellipsis2 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(3) {
            left: 26px;
            animation: lds-ellipsis2 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(4) {
            left: 45px;
            animation: lds-ellipsis3 0.6s infinite;
        }

        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }

        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(19px, 0);
            }
        }
    </style>
</head>

<body>
    <!-- Loading -->
    <loading>
        <div>
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p>Preparing NOJ</p>
        </div>
    </loading>
    <!-- Style -->
    <link rel="stylesheet" href="/static/fonts/Roboto/roboto.css">
    <link rel="stylesheet" href="/static/fonts/Montserrat/montserrat.css">
    <link rel="stylesheet" href="/static/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" href="/static/css/wemd-color-scheme.css">
    <link rel="stylesheet" href="/static/css/atsast.css">
    <link rel="stylesheet" href="/static/css/animate.min.css">
    <link rel="stylesheet" href="/static/fonts/MDI-WXSS/MDI.css">
    <link rel="stylesheet" href="/static/fonts/Devicon/devicon.css">
    <style>
        paper-card {
            display: block;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 30px;
            border-radius: 4px;
            transition: .2s ease-out .0s;
            color: #7a8e97;
            background: #fff;
            padding: 1rem;
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.15);
            margin-bottom: 2rem;
        }

        paper-card:hover {
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
        }

        fresh-container {
            display: block;
            all: initial;
            font-family: 'Montserrat';
        }

        fresh-container h1,
        fresh-container h2,
        fresh-container h3,
        fresh-container h4,
        fresh-container h5,
        fresh-container h6 {
            line-height: 1.2;
            margin-top: 1rem;
            margin-bottom: 16px;
            color: #000;
        }

        fresh-container h1 {
            font-size: 2.25rem;
            font-weight: 600;
            padding-bottom: .3em
        }

        fresh-container h2 {
            font-size: 1.75rem;
            font-weight: 600;
            padding-bottom: .3em
        }

        fresh-container h3 {
            font-size: 1.5rem;
            font-weight: 600
        }

        fresh-container h4 {
            font-size: 1.25rem;
            font-weight: 600
        }

        fresh-container h5 {
            font-size: 1rem;
            font-weight: 600
        }

        fresh-container h6 {
            font-size: 1rem;
            font-weight: 600
        }

        fresh-container p {
            line-height: 1.6;
            color: #333;
        }

        fresh-container>:first-child {
            margin-top: 0;
        }

        fresh-container>:last-child {
            margin-bottom: 0;
        }

        fresh-container pre {
            background-color: rgb(245, 245, 245);
            border: 1px solid #d6d6d6;
            border-radius: 3px;
            color: rgb(51, 51, 51);
            display: block;
            font-family: Consolas, "Liberation Mono", Menlo, Courier, monospace;
            font-size: .85rem;
            text-align: left;
            white-space: pre;
            word-spacing: normal;
            word-break: normal;
            word-wrap: normal;
            line-height: 1.4;
            tab-size: 8;
            hyphens: none;
            margin-bottom: 1rem;
            padding: .8rem;
            overflow: auto;
        }

        fresh-container li{
            margin-bottom: 1rem;
        }

        fresh-container img {
            max-width: 100%;
        }

        .cm-action-group {
            margin: 0;
            margin-bottom: 2rem;
            padding: 0;
            display: flex;
        }

        .cm-action-group>button {
            text-align: left;
            margin: .3125rem 0;
            border-radius: 0;
        }

        .cm-action-group i {
            display: inline-block;
            transform: scale(1.5);
            margin-right: 0.75rem;
        }

        separate-line {
            display: block;
            margin: 0;
            padding: 0;
            height: 1px;
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
        }

        separate-line.ultra-thin {
            transform: scaleY(0.5);
        }

        separate-line.thin {
            transform: scaleY(0.75);
        }

        separate-line.stick {
            transform: scaleY(1.5);
        }

        .cm-empty {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 10rem;
        }
        .immersive-container{
            height:100vh;
            width:100vw;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        left-side{
            width:40vw;
            /* width: calc(0.618 * (100vh - 4rem)); */
        }

        right-side{
            width:60vw;
            /* width:calc( 100vw - 0.618 * (100vh - 4rem)); */
            /* flex-grow: 1;
            flex-shrink: 1; */
        }

        top-side{
            display: flex;
            flex-grow: 1;
            flex-shrink: 1;
            width:100vw;
            justify-content: space-between;
            overflow: hidden;
        }

        bottom-side{
            height: 4rem;
            flex-grow: 0;
            flex-shrink: 0;
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
            border-top: 1px solid rgba(0, 0, 0, 0.15);
            background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
            padding: 0.5rem 1.25rem;
            display:flex;
            justify-content: space-between;
            align-items: center;
        }

        bootom-side button{
            margin-bottom:0;
        }

        left-side{
            overflow-y: scroll;
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
            padding: 3rem;
            padding-top: 0;
        }

        a.action-menu-item:hover{
            text-decoration: none;
        }

        left-side,right-side{
            display:block;
        }

        [class^="devicon-"], [class*=" devicon-"] {
            display:inline-block;
            transform: scale(1.3);
            padding-right:1rem;
            color:#7a8e97;
        }

        #cur_lang_selector > i{
            padding-right:0.25rem;
        }

        .dropdown-item{
            cursor: pointer;
        }

        .prob-header{
            color: #7a8e97;
            display: flex;
            /* justify-content: space-between; */
            align-items: center;
            padding-top: 2rem;
            padding-bottom: 2rem;
            position: sticky;
            top: 0;
            background: linear-gradient(0, rgba(250, 250, 250, 0) 0%, rgba(250, 250, 250, 1) 20%);
            z-index: 1;
        }

        .prob-header *{
            margin-bottom:0;
        }

        .prob-header > info-badge{
            display: inline-block;
            margin-left: 1rem;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu .dropdown-item.lang-selector{
            flex-wrap: nowrap;
        }

        .cm-scrollable-menu{
            height: auto;
            max-height: 61.8vh;
            overflow-x: hidden;
        }

        .btn-group .dropdown-menu {
            border-radius: .125rem;
        }

        .show>.btn-secondary.dropdown-toggle{
            color: #6c757d;
        }

        .cm-performance-optimistic{
            will-change: opacity;
        }

        .cm-delay{
            animation-delay: 0.2s;
        }

        .cm-refreshing{
            -webkit-transition-property: -webkit-transform;
            -webkit-transition-duration: 1s;
            -moz-transition-property: -moz-transform;
            -moz-transition-duration: 1s;
            -webkit-animation: cm-rotate 3s linear infinite;
            -moz-animation: cm-rotate 3s linear infinite;
            -o-animation: cm-rotate 3s linear infinite;
            animation: cm-rotate 3s linear infinite;
        }
        #problemSwitcher{
            display: inline-block;
        }
        #problemSwitcher > button{
            font-size: 2.25rem;
            font-weight: 600;
            padding-bottom: .3em;
            line-height: 1;
            color: #000;
        }

        #problemSwitcher a.dropdown-item > span{
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @-webkit-keyframes cm-rotate{
            from{-webkit-transform: rotate(0deg)}
            to{-webkit-transform: rotate(360deg)}
        }
        @-moz-keyframes cm-rotate{
            from{-moz-transform: rotate(0deg)}
            to{-moz-transform: rotate(359deg)}
        }
        @-o-keyframes cm-rotate{
            from{-o-transform: rotate(0deg)}
            to{-o-transform: rotate(359deg)}
        }
        @keyframes cm-rotate{
            from{transform: rotate(0deg)}
            to{transform: rotate(359deg)}
        }

    </style>

    <div class="immersive-container">
        <top-side>
            <left-side>
                <div class="prob-header animated pre-animated cm-performance-optimistic">
                    <button class="btn btn-outline-secondary" id="backBtn"><i class="MDI arrow-left"></i>  Back</button>
                    @if($contest_mode)
                        @if($contest_rule==1)
                            <info-badge data-toggle="tooltip" data-placement="top" title="Passed / Submission"><i class="MDI checkbox-multiple-marked-circle"></i> {{$detail['passed_count']}} / {{$detail['submission_count']}}</info-badge>
                        @else
                            <info-badge data-toggle="tooltip" data-placement="top" title="Total Points"><i class="MDI checkbox-multiple-marked-circle"></i> {{$detail["points"]}} Points</info-badge>
                        @endif
                     @else
                        <info-badge data-toggle="tooltip" data-placement="top" title="AC Rate"><i class="MDI checkbox-multiple-marked-circle"></i> {{$detail['ac_rate']}}%</info-badge>
                    @endif
                    <info-badge data-toggle="tooltip" data-placement="top" title="Time Limit"><i class="MDI timer"></i> {{$detail['time_limit']}}ms</info-badge>
                    <info-badge data-toggle="tooltip" data-placement="top" title="Memory Limit"><i class="MDI memory"></i> {{$detail['memory_limit']}}K</info-badge>
                </div>
                <div class="animated pre-animated cm-performance-optimistic cm-delay">
                    <link rel="stylesheet" href="/css/oj/{{$detail["oj_detail"]["ocode"]}}.css">
                    <fresh-container>
                        <h1>
                            @if($contest_mode)
                            <div class="dropdown" id="problemSwitcher">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$ncode}}</button>
                                <div class="dropdown-menu cm-scrollable-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; will-change: top, left; top: 40px; left: 0px;">
                                    @foreach($problem_set as $p)
                                        <a class="dropdown-item" href="@if($p["ncode"]==$ncode) # @else /contest/{{$cid}}/board/challenge/{{$p["ncode"]}} @endif">
                                            <span><i class="MDI {{$p["prob_status"]["icon"]}} {{$p["prob_status"]["color"]}}"></i> {{$p["ncode"]}}. {{$p["title"]}}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif {{$detail["title"]}}</h1>
                        <h2>Description:</h2>

                        {!!$detail["parsed"]["description"]!!}

                        @unless(trim($detail["parsed"]["input"])=="")

                        <h2>Input:</h2>

                        {!!$detail["parsed"]["input"]!!}

                        @endunless

                        @unless(trim($detail["parsed"]["output"])=="")

                        <h2>Output:</h2>

                        {!!$detail["parsed"]["output"]!!}

                        @endunless

                        @foreach($detail["samples"] as $ps)

                            <h2>Sample Input:</h2>

                            <pre>{!!$ps['sample_input']!!}</pre>

                            <h2>Sample Output:</h2>

                            <pre>{!!$ps['sample_output']!!}</pre>

                        @endforeach

                        @unless(trim($detail["parsed"]["note"])=="")

                        <h2>Note:</h2>

                        {!!$detail["parsed"]["note"]!!}

                        @endunless

                    </fresh-container>
                </div>
            </left-side>
            <right-side style="background: rgb(30, 30, 30);">
                <div id="vscode_container" style="width:100%;height:100%;">
                    <div id="vscode" style="width:100%;height:100%;"></div>
                </div>
            </right-side>
        </top-side>
        <bottom-side>
            <div style="color: #7a8e97" id="verdict_info" class="{{$status["color"]}}"><span id="verdict_circle"><i class="MDI checkbox-blank-circle"></i></span> <span id="verdict_text">{{$status["verdict"]}} @if($status["verdict"]=="Partially Accepted")({{round($status["score"]/$detail["tot_score"]*$detail["points"])}})@endif</span></div>
            <div>
                <button type="button" class="btn btn-secondary" id="historyBtn"> <i class="MDI history"></i> History</button>
                <div class="btn-group dropup">
                    <button type="button" class="btn btn-secondary dropdown-toggle" id="cur_lang_selector" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="{{$compiler_list[$pref]['icon']}} colored"></i> {{$compiler_list[$pref]['display_name']}}
                    </button>
                    <div class="dropdown-menu cm-scrollable-menu">
                        @foreach ($compiler_list as $c)
                            <button class="dropdown-item lang-selector" data-coid="{{$c['coid']}}" data-comp="{{$c['comp']}}" data-lang="{{$c['lang']}}" data-lcode="{{$c['lcode']}}"><i class="{{$c['icon']}} colored"></i> {{$c['display_name']}}</button>
                        @endforeach
                    </div>
                    </div>
                @if($contest_mode && $contest_ended)
                    <a href="/problem/{{$detail["pcode"]}}"><button type="button" class="btn btn-info" id="origialBtn"> <i class="MDI launch"></i> Original Problem</button></a>
                @else
                    <button type="button" class="btn btn-primary" id="submitBtn"> <i class="MDI send"></i> <span>Submit Code</span></button>
                @endif
            </div>

        </bottom-side>
    </div>
    <style>
        .sm-modal{
            display: block;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 30px;
            border-radius: 4px;
            transition: .2s ease-out .0s;
            color: #7a8e97;
            background: #fff;
            padding: 1rem;
            position: relative;
            /* border: 1px solid rgba(0, 0, 0, 0.15); */
            margin-bottom: 2rem;
            width:auto;
        }
        .sm-modal:hover {
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
        }
        .modal-title{
            font-weight: bold;
            font-family: roboto;
        }
        .sm-modal td{
            white-space: nowrap;
        }

        .modal-dialog {
            max-width:50vw;
            justify-content: center;
        }

        #submitBtn > i{
            display: inline-block;
        }

    </style>
    <div id="historyModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content sm-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="MDI history"></i> Submit History</h5>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Time</th>
                                <th scope="col">Memory</th>
                                <th scope="col">Language</th>
                                <th scope="col">Result</th>
                            </tr>
                        </thead>
                        <tbody id="history_container">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("load",function() {

        }, false);
    </script>
    <script src="/static/library/jquery/dist/jquery.min.js"></script>
    <script src="/static/js/popper.min.js"></script>
    <script src="/static/js/snackbar.min.js"></script>
    <script src="/static/js/bootstrap-material-design.js"></script>
    <script src="/static/vscode/vs/loader.js"></script>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
          tex2jax: {
            inlineMath: [ ['$$$','$$$'], ["\\(","\\)"] ],
            processEscapes: true
          }
        });
    </script>
    <script type="text/javascript" src="/static/library/mathjax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script>
        $(document).ready(function () { $('body').bootstrapMaterialDesign();$('[data-toggle="tooltip"]').tooltip(); });

        var historyOpen=false;
        var submission_processing=false;
        var chosen_lang="{{$compiler_list[$pref]['lcode']}}";
        var chosen_coid="{{$compiler_list[$pref]['coid']}}";
        var tot_points=parseInt("{{$detail["points"]}}");
        var tot_scores=parseInt("{{$detail["tot_score"]}}");

        $( ".lang-selector" ).click(function() {
            // console.log($( this ).data("lang"));
            var model = editor.getModel();
            monaco.editor.setModelLanguage(model, $( this ).data("lang"));
            $("#cur_lang_selector").html($( this ).html());
            chosen_lang=$( this ).data("lcode");
            chosen_coid=$( this ).data("coid");
        });

        $( "#historyBtn" ).click(function(){
            if(historyOpen) return;
            historyOpen=true;
            $.ajax({
                type: 'POST',
                url: '/ajax/submitHistory',
                data: {
                    pid: {{$detail["pid"]}},
                    @if($contest_mode) cid: {{$cid}} @endif
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(ret){
                    console.log(ret);
                    if(ret.ret==200){
                        $("#history_container").html("");
                        ret.data.history.forEach(ele => {
                            if(ele.verdict=="Partially Accepted"){
                                let real_score = Math.round(ele.score / tot_scores * tot_points);
                                $("#history_container").append(`
                                    <tr>
                                        <td>${ele.time}</td>
                                        <td>${ele.memory}</td>
                                        <td>${ele.language}</td>
                                        <td class="${ele.color}"><i class="MDI checkbox-blank-circle"></i> ${ele.verdict} (${real_score})</td>
                                    </tr>
                                `);
                            }else{
                                $("#history_container").append(`
                                    <tr>
                                        <td>${ele.time}</td>
                                        <td>${ele.memory}</td>
                                        <td>${ele.language}</td>
                                        <td class="${ele.color}"><i class="MDI checkbox-blank-circle"></i> ${ele.verdict}</td>
                                    </tr>
                                `);
                            }
                        });
                    }
                    $('#historyModal').modal();
                    historyOpen=false;
                }, error: function(xhr, type){
                    console.log('Ajax error while posting to submitHistory!');
                    historyOpen=false;
                }
            });
        });

        $( "#submitBtn" ).click(function() {
            if(submission_processing) return;
            submission_processing = true;
            $("#submitBtn > i").removeClass("send");
            $("#submitBtn > i").addClass("autorenew");
            $("#submitBtn > i").addClass("cm-refreshing");
            $("#submitBtn > span").text("Submitting");
            // console.log(editor.getValue());
            $("#verdict_text").text("Submitting...");
            $("#verdict_info").removeClass();
            $("#verdict_info").addClass("wemd-blue-text");
            $.ajax({
                type: 'POST',
                url: '/ajax/submitSolution',
                data: {
                    lang: chosen_lang,
                    pid:{{$detail["pid"]}},
                    pcode:"{{$detail["pcode"]}}",
                    cid:"{{$detail["contest_id"]}}",
                    iid:"{{$detail["index_id"]}}",
                    oj:"{{$detail["oj_detail"]["ocode"]}}",
                    coid: chosen_coid,
                    solution: editor.getValue(),
                    @if($contest_mode) contest: {{$cid}} @endif
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(ret){
                    console.log(ret);
                    if(ret.ret==200){
                        // submitted
                        $("#verdict_text").text("Waiting");
                        $("#verdict_info").removeClass();
                        $("#verdict_info").addClass("wemd-blue-text");
                        var tempInterval=setInterval(()=>{
                            $.ajax({
                                type: 'POST',
                                url: '/ajax/judgeStatus',
                                data: {
                                    sid: ret.data.sid
                                },
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }, success: function(ret){
                                    console.log(ret);
                                    if(ret.ret==200){
                                        if(ret.data.verdict=="Partially Accepted"){
                                            let real_score = Math.round(ret.data.score / tot_scores * tot_points);
                                            $("#verdict_text").text(ret.data.verdict + ` (${real_score})`);
                                        } else{
                                            $("#verdict_text").text(ret.data.verdict);
                                        }
                                        $("#verdict_info").removeClass();
                                        $("#verdict_info").addClass(ret.data.color);
                                        if(ret.data.verdict!="Waiting" && ret.data.verdict!="Judging"){
                                            clearInterval(tempInterval);
                                        }
                                    }
                                }, error: function(xhr, type){
                                    console.log('Ajax error while posting to judgeStatus!');
                                }
                            });
                        },5000);
                    }else{
                        console.log(ret.desc);
                        $("#verdict_text").text("System Error");
                        $("#verdict_info").removeClass();
                        $("#verdict_info").addClass("wemd-black-text");
                    }
                    submission_processing = false;
                    $("#submitBtn > i").addClass("send");
                    $("#submitBtn > i").removeClass("autorenew");
                    $("#submitBtn > i").removeClass("cm-refreshing");
                    $("#submitBtn > span").text("Submit Code");
                }, error: function(xhr, type){
                    console.log('Ajax error!');
                    $("#verdict_text").text("System Error");
                    $("#verdict_info").removeClass();
                    $("#verdict_info").addClass("wemd-black-text");
                    submission_processing = false;
                    $("#submitBtn > i").addClass("send");
                    $("#submitBtn > i").removeClass("autorenew");
                    $("#submitBtn > i").removeClass("cm-refreshing");
                    $("#submitBtn > span").text("Submit Code");
                }
            });
        });

        document.getElementById("backBtn").addEventListener("click",function(){
            @if($contest_mode)
                location.href="/contest/{{$cid}}/board/challenge/";
            @else
                location.href="/problem/{{$detail["pcode"]}}";
            @endif
        },false);

        window.addEventListener("load",function() {
            $('loading').css({"opacity":"0","pointer-events":"none"});

            $(".pre-animated").addClass("fadeInLeft");

            require.config({ paths: { 'vs': '/static/vscode/vs' }});

            // Before loading vs/editor/editor.main, define a global MonacoEnvironment that overwrites
            // the default worker url location (used when creating WebWorkers). The problem here is that
            // HTML5 does not allow cross-domain web workers, so we need to proxy the instantiation of
            // a web worker through a same-domain script

            window.MonacoEnvironment = {
                getWorkerUrl: function(workerId, label) {
                    return `data:text/javascript;charset=utf-8,${encodeURIComponent(`
                    self.MonacoEnvironment = {
                        baseUrl: '/static/vscode/'
                    };
                    importScripts('/static/vscode/vs/base/worker/workerMain.js');`
                    )}`;
                }
            };

            require(["vs/editor/editor.main"], function () {
                editor = monaco.editor.create(document.getElementById('vscode'), {
                    value: "{!!$submit_code!!}",
                    language: "{{$compiler_list[$pref]['lang']}}",
                    theme: "vs-dark",
                    fontSize: 16,
                    formatOnPaste: true,
                    formatOnType: true,
                    automaticLayout: true
                });
                $("#vscode_container").css("opacity",1);
            });
        }, false);
    </script>
</body>

</html>
