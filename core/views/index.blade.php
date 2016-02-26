<?php

use App\Http\Helpers\ModuleHelper;
use App\Http\Helpers\TriggerHelper;
use App\Http\Helpers\HookHelper;
?>
@extends('template')
@section('content')
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
    <h2 class="page-header"><i class="fa fa-whatsapp"></i> Chat</h2>
    <div class="row">
        <div class="col-md-12" id="someError" style="display: none;">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Tenemos problemas...</h4>
                Estamos teniendo problemas para cargar  algunos mensajes del chat, pulsa <a href="">aquí</a> para reiniciar el chat.
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Conversaciones</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="overflow: scroll; max-height: 400px;">
                    <table class="table table-bordered" id="tableThreads"></table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- DIRECT CHAT -->
            <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Ventana de chat</h3>
                    <div class="box-tools pull-right" >  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages" id="chatContainer">

                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <form action="#" method="post">
                        <div class="input-group">
                            <input name="message" placeholder="Escriba su mensaje ..." class="form-control" id="messageToSend" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning btn-flat" onclick="sendMessage(jQuery('#messageToSend').val())"><i class="fa fa-paper-plane-o"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- /.box-footer-->
            </div>
            <!--/.direct-chat -->
        </div>
        <!-- /.col -->
    </div>
</section>
<script>
    //Global variables
    var messagesQuantity = 0;
    var fk_sender = 0;

    //
    window.onload = function () {
        loadThreadList();
        //Set the auto-refresh interval for the thread list
        window.setInterval(function () {
            loadThreadList();
        }, 25000);
        //Set the auto-refresh interval for the chat
        window.setInterval(function () {
            loadThread(fk_sender);
        }, 1000);
    };

    /*
     * This function will load the chat thread
     */
    function loadThreadList()
    {
        //Empty the thread list
        jQuery("#tableThreads").html("");
        //Download the thread list
        jQuery.ajax({
            url: "{{URL::to('/chat/getThreads')}}",
            method: "POST"
        }).done(function (data) {
            var obj = JSON.parse(data);
            for (var i = 0; i < obj.length; i++) {
                console.log(checkQty(obj[i].sender));
                if (checkQty(obj[i].sender) > 0)
                    jQuery("#tableThreads").append("<tr><td onclick='setThreadListener(\"" + obj[i].sender + "\")'>" + obj[i].sender + " <span data-toggle='tooltip' class='badge bg-green pull-right'>" + checkQty(obj[i].sender) + "</span></td></tr>");
                else
                    jQuery("#tableThreads").append("<tr><td onclick='setThreadListener(\"" + obj[i].sender + "\")'>" + obj[i].sender + "</td></tr>");
            }
        }).fail(function () {
            jQuery("#someError").show();
        });
    }

    /*
     * This function check's how many unreaden messages are for a specific sender 
     */
    function checkQty(sender)
    {
        var result;
        jQuery.ajax({
            url: "{{URL::to('/chat/getCount')}}",
            method: "POST",
            data: {sender: sender},
            async: false
        }).done(function (data) {
            result = parseInt(data);
        }).fail(function () {
            jQuery("#someError").show();
        });
        //Return the data
        return result;
    }

    function setThreadListener(sender){fk_sender = sender;}

    /*
     * This function load's the  
     */
    function loadThread()
    {
        //Download the messages
        jQuery.ajax({
            url: "{{URL::to('/chat/getMessages')}}",
            method: "POST",
            data: {sender: fk_sender}
        }).done(function (data) {
            var obj = JSON.parse(data);
            
            //Re-center the chat window (only if need)
            if (messagesQuantity != obj.length) {
            //Empty the chat container
            jQuery('#chatContainer').html("");
                //For-each object, create a row in the chat view
                for (var i = 0; i < obj.length; i++) {
                    //Check if message is mine
                    if (obj[i].mine != 1)
                        jQuery("#chatContainer").append('<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right"></span><span class="direct-chat-timestamp pull-left">' + obj[i].created_at + '</span></div><img class="direct-chat-img" src="http://icons.iconarchive.com/icons/graphicloads/100-flat-2/256/chat-2-icon.png" alt="message user image"><div class="direct-chat-text">' + obj[i].message + '</div></div>');
                    else
                        jQuery("#chatContainer").append('<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left"></span><span class="direct-chat-timestamp pull-right">' + obj[i].created_at + '</span></div><img class="direct-chat-img" src="http://icons.iconarchive.com/icons/graphicloads/100-flat-2/256/chat-2-icon.png" alt="message user image"><div class="direct-chat-text">' + obj[i].message + '</div></div>');
                }
                jQuery('#chatContainer').scrollTop($('#chatContainer')[0].scrollHeight);
                messagesQuantity = obj.length;
            }
        }).fail(function () {
            jQuery("#someError").show();
        });
    }
    
    /*
     * This function will send the message 
     */
    function sendMessage(message)
    {
        //Send the request
        jQuery.ajax({
            url: "{{URL::to('/chat/sendMessage')}}",
            method: "POST",
            data: {sender: fk_sender, message: jQuery("#messageToSend").val(), mine: 1}
        }).done(function (data) {
        }).fail(function () {
            jQuery("#someError").show();
        });
        //Empty the message field
        jQuery("#messageToSend").val("");
    }
</script>
@stop