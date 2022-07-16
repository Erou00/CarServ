@extends('layouts.app')
@section('styles')
<script src="https://kit.fontawesome.com/39e20eb3b4.js" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<style>


.card {
    background: #fff;
    transition: .5s;
    border: 0;
    margin-bottom: 30px;
    border-radius: .55rem;
    position: relative;
    width: 100%;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
}
.chat-app .people-list {
    width: 280px;
    position: absolute;
    left: 0;
    top: 0;
    padding: 20px;
    z-index: 7
}

.chat-app .chat {
    margin-left: 280px;
    border-left: 1px solid #eaeaea
}

.people-list {
    -moz-transition: .5s;
    -o-transition: .5s;
    -webkit-transition: .5s;
    transition: .5s
}

.people-list .chat-list li {
    padding: 10px 15px;
    list-style: none;
    border-radius: 3px
}

.people-list .chat-list li:hover {
    background: #efefef;
    cursor: pointer
}

.people-list .chat-list li.active {
    background: #efefef
}

.people-list .chat-list li .name {
    font-size: 15px
}

.people-list .chat-list img {
    width: 45px;
    border-radius: 50%
}

.people-list img {
    float: left;
    border-radius: 50%
}

.people-list .about {
    float: left;
    padding-left: 8px
}

.people-list .status {
    color: #999;
    font-size: 13px
}

.chat .chat-header {
    padding: 15px 20px;
    border-bottom: 2px solid #f4f7f6
}

.chat .chat-header img {
    float: left;
    border-radius: 40px;
    width: 40px
}

.chat .chat-header .chat-about {
    float: left;
    padding-left: 10px
}

.chat .chat-history {
    padding: 20px;
    border-bottom: 2px solid #fff
}

.chat .chat-history ul {
    padding: 0
}

.chat .chat-history ul li {
    list-style: none;
    margin-bottom: 30px
}

.chat .chat-history ul li:last-child {
    margin-bottom: 0px
}

.chat .chat-history .message-data {
    margin-bottom: 15px
}

.chat .chat-history .message-data img {
    border-radius: 40px;
    width: 40px
}

.chat .chat-history .message-data-time {
    color: #434651;
    padding-left: 6px
}

.chat .chat-history .message {
    color: #444;
    padding: 18px 20px;
    line-height: 26px;
    font-size: 16px;
    border-radius: 7px;
    display: inline-block;
    position: relative
}

.chat .chat-history .message:after {
    bottom: 100%;
    left: 7%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-bottom-color: #fff;
    border-width: 10px;
    margin-left: -10px
}

.chat .chat-history .my-message {
    background: #efefef
}

.chat .chat-history .my-message:after {
    bottom: 100%;
    left: 30px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-bottom-color: #efefef;
    border-width: 10px;
    margin-left: -10px
}

.chat .chat-history .other-message {
    background: #e8f1f3;
    text-align: right
}

.chat .chat-history .other-message:after {
    border-bottom-color: #e8f1f3;
    left: 93%
}

.chat .chat-message {
    padding: 20px
}

.online,
.offline,
.me {
    margin-right: 2px;
    font-size: 8px;
    vertical-align: middle
}

.online {
    color: #86c541
}

.offline {
    color: #e47297
}

.me {
    color: #1d8ecd
}

.float-right {
    float: right
}

.clearfix:after {
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0
}

@media only screen and (max-width: 767px) {
    .chat-app .people-list {
        height: 465px;
        width: 100%;
        overflow-x: auto;
        background: #fff;
        left: -400px;
        display: none
    }
    .chat-app .people-list.open {
        left: 0
    }
    .chat-app .chat {
        margin: 0
    }
    .chat-app .chat .chat-header {
        border-radius: 0.55rem 0.55rem 0 0
    }
    .chat-app .chat-history {
        height: 300px;
        overflow-x: auto
    }
}

@media only screen and (min-width: 768px) and (max-width: 992px) {
    .chat-app .chat-list {
        height: 650px;
        overflow-x: auto
    }
    .chat-app .chat-history {
        height: 600px;
        overflow-x: auto
    }
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
    .chat-app .chat-list {
        height: 480px;
        overflow-x: auto
    }
    .chat-app .chat-history {
        height: calc(100vh - 350px);
        overflow-x: auto
    }
}

.messages-content{
position: relative;
  height: 460px;
  background-color: #fff;
  padding: 15px;
  overflow-y: scroll;


}

.msg-body{

        }

        .msg-body, .media-left, .media-right {

            vertical-align: top;
        }

        .mf-content {
            padding: 12px 17px 13px;
            border-radius: 2px;
            display: inline-block;
            max-width: 80%;
        }

        .mf-date {
            display: block;
            color: #B3B3B3;
            margin-top: 7px;
            padding: 0px 17px 13px;
        }

        .message-feed:not(.right) .mf-content {
            background: #ffd700;
            color: #000;
            font-weight: bold;
            box-shadow: 0 2px 2px 0 rgb(0 0 0 / 14%), 0 3px 1px -2px rgb(0 0 0 / 20%), 0 1px 5px 0 rgb(0 0 0 / 12%);
            position: relative;
        }
        .message-feed.right .mf-content {
                background: #000;
                color: #fff;
                font-weight: bold;
                position: relative;
        }

        .message-feed.right {
            text-align: right;
            margin-right: 8px;
        }

        .message-feed .msg-body img{
            float: left;
            margin-right: 3px;
            border-radius: 50%;
        }

        .message-feed.right .msg-body img{
            float: right;
            margin-left: 3px
        }

        .message-feed .msg-body .mf-content:before {
          content: '';
          display: block;
          position: absolute;

          border: 18px solid transparent;
          border-bottom-width: 0;
      }
    .message-feed.left .msg-body .mf-content:before {
        left: -30px;
        top: 0px;
        border-right-color: #ffd700;
        border-right-width: 12px;
    }

    .message-feed.right .msg-body .mf-content:before {
      right: -30px;
      top: 0px;
  border-left-color: #000;
  border-left-width: 12px;
    }

    .messages-content::-webkit-scrollbar {
width: 1em;
}

.messages-content::-webkit-scrollbar-track {
box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
}

.messages-content::-webkit-scrollbar-thumb {
background-color: rgb(0, 0, 0);
outline: 1px solid slategrey;
}

</style>
@endsection
@section('content')
<div class="container mt-5 car" >
    <div class="main-body">

      <messages :Cars="{{$cars}}" :User="{{Auth::user()}}"></messages>
    </div>
</div>
@endsection

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

@endsection
