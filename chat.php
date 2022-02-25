<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Chat - chatting</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/chat.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="./js/chat.js"></script>
</head>
<body>

<?php
    session_start();
    if(isset($_SESSION['userid'])){
        $userid = $_SESSION['userid'];
    }else{
        $userid = "";
        echo ("
            <script>
                alert('로그인이 필요한 페이지입니다. 로그인 후 이용 바랍니다.');
                location.href= './main.html';
            </script>
        ");
    }
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }else{
        $username = "";
    }
?>
    <span class="hide" id="user_id"><?=$userid?></span>
    <span class="hide" id="user_name"><?=$username?></span>

    <script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-database.js"></script>
    
    <script>
        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyC_Th-PzOV67EtoikVOn3aAuhjDKuMlulc",
            authDomain: "nifty-cabinet-320607.firebaseapp.com",
            databaseURL: "https://nifty-cabinet-320607-default-rtdb.firebaseio.com",
            projectId: "nifty-cabinet-320607",
            storageBucket: "nifty-cabinet-320607.appspot.com",
            messagingSenderId: "632965396357",
            appId: "1:632965396357:web:b970f13a9036f3bb32b6ec"
        };
      
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        //var userName = prompt("이름을 입력해 주세요.");
        var userName = document.getElementById("user_name").innerText;

        //#1. 메시지 보내기
        function sendMessage(){
            var message = document.getElementById("message").value;
            
            if(message.length < 1){
                console.log("메시지 없음");
                alert("메시지 입력란에 메시지를 입력해주시기 바랍니다.");
            }else{
                firebase.database().ref("messages").push().set({
                    "sender" : userName,
                    "message" : message
                })
            }
            document.getElementById("message").value = "";  //모든 value 값을 전송 후 기존 입력상자를 비운다.
            document.getElementById("message").focus();  //모든 value 값을 전송 후 입력 상자에 초점을 맞춘다.

            return false;  //form 태그에서 전송을 했기 때문에 action=""(form 태그의 기본 속성)에의해서 새로고침이 발생되는 것을 막는다.
        }

        //#2. 메시지 리스트 가져오기 (내가 보낸메시지와 상대방들이 보낸 메시지에 대한 구분)
        firebase.database().ref("messages").on("child_added", function(snapshot){
            console.log(snapshot);
            console.log(snapshot.key);
            console.log(snapshot.val().sender);
            console.log(snapshot.val().message);

            if(snapshot.val().sender == userName){  //내가 작성한 메시지
                var html = "";
                html += "<li class='mine' id='message-"+snapshot.key+"'>";
                html += "<p>"+snapshot.val().sender+"</p>";
                html += "<span>"+snapshot.val().message;
                html += "<button data-id='"+snapshot.key+"' onclick='deleteMessage(this);'><span>×</span></button>";
                html += "</span>";
                html += "</li>";

                document.getElementById("messages").innerHTML += html;
            }else{  //타인(들)이 작성한 메시지
                var html = "";
                html += "<li class='other' id='message-"+snapshot.key+"'>";
                html += "<p>"+snapshot.val().sender+"</p>";
                html += "<span>"+snapshot.val().message+"</span>";
                html += "</li>";

                document.getElementById("messages").innerHTML += html;
            }

            var chatscroll = document.getElementById("messages");
            chatscroll.scrollTop = chatscroll.scrollHeight;
        });

        //#3. 메시지 삭제 : 삭제 기능 함수문("x" 버튼 클릭시)
        function deleteMessage(self){
            var messageId = self.getAttribute("data-id");
            firebase.database().ref("messages").child(messageId).remove();
        }

        //#4. 삭제된 메시지에 대한 표현 넣기(문구표현 "삭제된 메시지입니다.")
        firebase.database().ref("messages").on("child_removed", function(snapshot){
            //문서상에 삭제된 항목을 대체 문구로 변경
            if(snapshot.val().sender == userName){
                document.getElementById("message-"+snapshot.key).innerHTML = "<li class='mine'><span class='deleteMsg'>삭제된 메시지입니다.</span></li>";
            }else{
                document.getElementById("message-"+snapshot.key).innerHTML = "<li class='other'><span class='deleteMsg'>삭제된 메시지입니다.</span></li>";
            }
        });
      </script>


      <header>
          <div class="logo">
              <a href=""><img src="./img/logo.svg" alt="mychat logo"></a>

              <img class="logout_btn" src="./img/logout.svg" title="logout" alt="logout" onclick="location.href='./logout.php'">
          </div>
      </header>
      <section>
          <!--채팅 내용과 입력 상자를 담을 장소-->
          <article>
              <ul id="messages">
                
              </ul> 
          </article>
          <form id="chat_msg" name="chat_msg" onsubmit="return sendMessage();">
            <textarea name="txtmsg" id="message" placeholder="메시지를 입력하세요" autocomplete="off"></textarea>
            <input type="submit" value="보내기">
          </form>
      </section>




</body>
</html>