	<style>
    .ion-row{
  padding: 10px 15px;
  // border-bottom: 0.5px solid rgb(212, 210, 210);
  font-size: 1.15em !important;
}
.col{
  padding: 0 !important;
}
.ion-left{
  display: inline-block;
  width: 92%;
  vertical-align: middle;
}
.ion-right{
  display: inline-block;
  width: 5%;
  vertical-align: middle;
}
.ion-right ion-icon {
  // font-size: 1em !important;
  color: #b3b2b2;
}
ion-row div{
  // font-size: 1em !important;
}
.row-left{
  width: 35%;
}
.row-right{
  width: 65%;
}
.ion-number{
  color: #FF6200 !important;
}
.span-title{
  margin-right: 10px;
}
.deposit{
  color: #33971f !important;
}
.content-left{
  display: inline-block;
  width: 40% !important;
  color: #000000;
  text-align: left;
}
.content-right{
  display: inline-block;
  text-align: right;
  width: 60% !important;
  // float: right;
  white-space: nowrap; 
  overflow: hidden;
  text-overflow: ellipsis;
  color: #000000;
}
.select-icon{
  color: #11A416;
}
.item-native{
  border: 0.5px solid #C1BEBE;
  border-radius: 7px;
  /* background-color: #F0F0F0; */
}
select {
  -webkit-appearance: none; 
  -moz-appearance: none;
  -ms-appearance: none;
  appearance: none;
  /* outline: 0; */
  /* box-shadow: none; */
  /* border: 0 !important; */
  background: #FFF;
  background-image: none;
}
/* Remove IE arrow */
select::-ms-expand {
  display: none;
}
/* Custom Select */
.select {
  position: relative;
  display: flex;
  /* width: 20em; */
  height: 2em;
  border: 1px solid #C1BEBE;
  line-height: 2.2em;
  // background: #2c3e50;
  overflow: hidden;
  border-radius: .25em;
}
select {
  flex: 1;
  padding: 0 .5em;
  color: #000;
  line-height: 20px;
  cursor: pointer;
  border: transparent;
}
/* Arrow */
.select::after {
  content: '\25BC';
  position: absolute;
  top: 0;
  right: 0;
  color: #11A416;
  font-size: 14px;
  line-height: 2.5em;
  padding: 0 1em;
  /* background: #FFF; */
  cursor: pointer;
  pointer-events: none;
  transition: .25s all ease;
}
/* Transition */
.select:hover::after {
  color: #11A416;
}
.top-logo{
  width: 55px;
  vertical-align: middle;
  padding: 3px;
}

/* //number button  */
.login-top{
width: 100%;
text-align: center;
}
.login-key-title{
font-size: 1.7em;
margin-top: -95%;
color: #383A3A;
position: relative;
font-weight: 600;
display: flex;
justify-content: center;
/* //   margin-top: 12%; */
}
.mid-point{
margin: 0 10px;
}
.east-side{
margin-right: 10px;
}
.west-side{
margin-left: 10px;
}
ion-icon{
/* // color: var(--defaultColor); */
font-size: 2.1em !important;
}
.login-keyboard{
/* // padding: 20px 40px 0 40px;
// padding: 5vw 5vw 5vw 5vw;
// position: fixed; */
left: 0px;
bottom: 0px;
height: 60%;
width: 100%;
background: #FFF;
}
.login-keyboard{
padding: 6px 40px 0 40px;
}
.btn-num{
width: 60px;
height: 60px;
border-radius: 50%;
background: #fff !important;
border: 1px solid var(--defaultColor);
color: var(--defaultColor);
font-size: 34px;
}
.login-forgot{
white-space: pre-line !important;
font-size: 14px !important;
}
:focus {
outline: unset !important;
}
.middle{
vertical-align: middle;
}
.num-del{
margin-left: -1.5px !important;
/* // font-size: 1em !important; */
.icon-inner{
  margin-top: 10% !important;
  height: 80% !important;
}
}
.span-forgot{
  font-size: 2em;
  /* vertical-align: middle; */
  padding: 4%;
  color: #27A323;
  font-weight: 600;
  //font-size: 1.7em;
  /* margin-top: -84%; */
  /* color: #383A3A; */
  position: relative;
  font-weight: 600;
  display: flex;
  justify-content: center;
  justify-content: center;
}
.circle-point{
width: 20px;
height: 20px;
border: solid 2px var(--defaultColor);
border-radius: 50%;
display: inline-block;
}
.circle-point-on{
background-color: var(--defaultColor);
}
ion-content{
display: flex !important;
align-items: center !important;
/* // --offset-top: unset !important;
// --offset-bottom: unset !important; */
}
.scroll-content{ margin-top: unset !important; }
.login-top{
width: 100%;
text-align: center;
}

/* //buttom */
button, a { 
/* // padding: 13px 38px; */
font-size: 18px;
    font-weight: bold;
    line-height: 1;
    position: relative;
    border: 2px solid #FFFFFF;
    border-radius: 28px;
    background: transparent;
    background-clip: padding-box;
    color: #FFF;
    outline: none;
    cursor: pointer;
    text-decoration: none;
}

button.fixsize{
padding: 10px 54px;
}

button::after, a::after {
  position: absolute;
  top: -2px;
  bottom: -2px;
  left: -2px;
  right: -2px;
  background: linear-gradient(to right, #27A323, #27A323);
  content: '';
  z-index: -1;
  border-radius: 8px;
}
.wrapper {
  display: -webkit-box;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    /* margin-top: -7px; */
    margin-top: 24px;
}
.text-cener{
  text-align: center;
}
#watermark {
  width: 40%;
  position: relative;
  overflow: hidden;
}

#watermark img {
  position: absolute;
  top: 0;
  left: 0;
  color: #fff;
  font-size: 18px;
  pointer-events: none;
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
}

#repeat { 
  content: "";
  display: block;
  width: 100%;
  height: 100%;
  position: absolute;
  background-image: url(https://dev.policehospital-coop.com/APIs/img/logopghcoop.png);
  background-size: 315px 276px;
  background-position: 18px 203px;
  background-repeat: no-repeat;
  opacity: 0.2; }

ion-content{
 --ion-background-color:#1A864B;
  }

  .body-watermark{
    position: absolute;
    width: 40%;
    margin-top: 20%;
    margin-left: 30%;
    opacity: 0.09;
  }

  /* //toudepad */
  ::-webkit-scrollbar{width:2px;height:2px;}
::-webkit-scrollbar-button{width:2px;height:2px;}

div{
  box-sizing:border-box;
}

body {
  background: #111;
}

.horizontal-scroll-wrapper{
  // position:absolute;
  display: block;
  top: 0;
  left: 0;
  width: 80px;
  max-height: 418px;
  margin: 0;
  /* background: #abc; */
  overflow-y: auto;
  overflow-x: hidden;
  transform: rotate(-90deg) translateY(-80px);
  transform-origin: right top;
}
.horizontal-scroll-wrapper > div{
  display:block;
  padding:5px;
  transform:rotate(90deg);
  transform-origin: right top;
}

.squares{
  padding:60px 0 0 0;
}

.squares > div{
  width:60px;
  height:60px;
  margin:10px;
}

.rectangles{
  top:100px;
  padding:100px 0 0 0;
}
.rectangles > div{
  width:140px;
  height:38px;
  margin:40px 8px;
  padding:5px;
  transform:rotate(90deg) translateY(80px);
  transform-origin: right top;
}

input {
  padding:5px;
  margin:5px 0;
  outline: none;
  }
  input:focus,
  input:active {
  border-color: transparent;
  border-bottom: 2px solid #1c87c9;
  }

  input#myOutline {
    padding: 5px;
    margin: 5px 0;
    outline: none;
    border-color: transparent;
    border-bottom: 0.5px solid #707070;
    width: 299px;
    font-size: 22px;
    text-align: center;
}

::placeholder {
  color: #F0F0F0;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
 color: #F0F0F0;
}

::-ms-input-placeholder { /* Microsoft Edge */
 color: #F0F0F0;
}

ion-content{
  padding: 2rem;
}

    </style>

<div class="page-header font-H3">โอนเงินระหว่างบัญชีตนเอง</div>
    <ion-row class="center">
      <!-- <ion-col size="12"><img class="center" src="../../../assets/{{logo.logoPath}}" ></ion-col> -->
    </ion-row>
    <ion-row class="center">
      <ion-col size="12"><ion-icon class="color-icon center" mode="ios"
          name="checkmark-circle-outline"></ion-icon></ion-col>
    </ion-row>
    <h2 style="margin-left: 21px;font-weight: bold;color: #2B2B2B;margin-top:
      -14px;text-align: center;">ทำการโอนเงินเสร็จสิ้น</h2>
    <h2 style="margin-left: 21px;font-weight: bold;color: #2B2B2B;margin-top:
      -14px;text-align: center;">ท่านสามารถดูรายการเคลื่อนไหวได้</h2>
    <h2 style="margin-left: 21px;font-weight: bold;color: #2B2B2B;margin-top:
      -14px;text-align: center;">ที่เมนูเงินฝาก</h2>

    <!-- <div  class="wrapper">
    <div id="myBtn" style="
    font-size: 20px;
    font-weight: bold;
    line-height: 1;
    position: relative;
    border-radius: 28px;
    background: #27A323;
    background-clip: padding-box;
    color: #FFF;
    outline: none;
    width: 40%;
    padding: 5px;
    text-align: center;
    " class="fixsize" (click)="getSucessSCB()">ดูรายการเคลื่อนไหว</div>
  </div> -->
    <div class="wrapper">
      <div id="myBtn" style="font-size: 20px;
        font-weight: bold;
        line-height: 1;
        position: relative;
        border-radius: 28px;
        background-clip: padding-box;
        color: #FFF;
        outline: none;
        width: 67%;
        padding: 10px;
        text-align: center;"class="fixsize color-primary-bg"
        (click)="getSucessSCB()">ดูรายการเคลื่อนไหว</div>
    </div>
    <!-- <div class="ion-row">
        <ion-row style="
        margin-bottom: 7%;
    ">
          <ion-col size="6"><div>รหัสอ้างอิง</div></ion-col>
          <ion-col size="6"><div class="right-side">{{refNo}}</div></ion-col> 
      </ion-row>
      <ion-row style="
      margin-bottom: 7%;
  ">
        <ion-col size="6"><div>จากบัญชีสหกรณ์</div></ion-col>
        <ion-col size="6"><div class="right-side">{{memname}}</div><br><div class="right-side">{{accountNoKu}}</div></ion-col> 
    </ion-row>
    <ion-row style="
    margin-bottom: 7%;
">
      <ion-col size="6"><div>ไปยังบัญชี SCB</div></ion-col>
      <ion-col size="6">
        <div class="right-side">{{memname}}</div><br>
        <div class="right-side">{{accountNo}}</div></ion-col> 
  </ion-row>
  <ion-row style="
  margin-bottom: 7%;
">
    <ion-col size="6"><div>จำนวน</div></ion-col>
    <ion-col size="6"><div class="right-side">{{amount}} บาท</div></ion-col> 
</ion-row>
<ion-row style="
margin-bottom: 7%;
">
  <ion-col size="6"><div>ค่าธรรมเนียม</div></ion-col>
  <ion-col size="6"><div class="right-side">0.00 บาท</div></ion-col> 
</ion-row>
<ion-row style="
margin-bottom: 7%;
">
  <ion-col size="6"><div>วันที่/เวลา</div></ion-col>
  <ion-col size="6"><div class="right-side">{{time}} น.</div></ion-col> 
</ion-row>

<div class="wrapper" style="margin-top: 3em;">
  <div id="myDIV" style="font-size: 20px;margin-top: 2%; font-weight: bold; line-height: 1; position: relative; border: 2px solid #27A323;
  border-radius: 28px; background: #27A323; background-clip: padding-box; color: #FFF; outline: none; width: 40%; padding: 5px; text-align: center;"
  class="fixsize" (click)="getSucessSCB()">ต่อไป</div>
  <div id="myDIV1" style="font-size: 20px;margin-top: 2%; font-weight: bold; line-height: 1; position: relative; border: 2px solid #27A323;
  border-radius: 28px; background: #27A323; background-clip: padding-box; color: #FFF; outline: none; width: 40%; padding: 5px; text-align: center;margin-left:3px"
  class="fixsize" (click)="shareImg()"><svg id="myDIV1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="15" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#ffffff"><path d="M6.86656,151.60188l-6.86656,-0.12094c-0.06719,-1.88125 -1.23625,-46.17125 27.99031,-76.4325c16.59531,-17.15969 39.56,-26.18969 68.32969,-26.84812v-35.83781l74.4975,56.4375l-74.4975,56.45094v-35.77062c-84.07844,1.58562 -89.39969,61.51687 -89.45344,62.12156z"></path></g></g></svg>แชร์</div>
  </div> -->
    <!-- <ion-row>
  <ion-col size="6"><div>บันทึกภาพเก็บไว้</div></ion-col>
  <ion-col size="6" (click)="shareImg()"><div class="right-side">แชร์</div></ion-col> 
</ion-row>
<hr style="
    border: 0.5px solid #707070;
    width: 98%;
    height: 0px !important;
    margin-top: 3em;
">  
<div class="wrapper">
<button class="fixsize" (click)="getSucessSCB()">เสร็จสิ้น</button>
</div> -->
    <!-- </div> -->
  </div>


  <!-- //bill -->
  <div *ngIf="navCtrls === 'bill'">
    <div style="
    margin-top: 20px;
    margin-left: 20px;
    margin-right: 20px;
    margin-bottom: 20px;
    background-color: #FFF;
">
      <div id="repeat">
      </div>

      <!-- <h2 style="    /* margin-left: 21px; */
      font-size: 31px;
      font-weight: bold;
      text-align: center;">โอนเงินระหว่างบัญชีตนเอง</h2> -->
      <!-- <div class="page-header font-H3">โอนเงินระหว่างบัญชีตนเอง</div> -->
      <ion-row style="
      background: #1a864b14;
  ">
        <ion-col style="text-align: center;" size="3"><img src="https://dev.policehospital-coop.com/APIs/img/logopghcoop.png" style="
          width: 60%;
      "></ion-col>
        <ion-col style="margin-top: 15px;" size="8"><div>สหกรณ์ออมทรัพย์โรงพยาบาลตำรวจ จำกัด</div></ion-col>
      </ion-row>
      <ion-row class="center">
        <ion-col size="12"><ion-icon class="color-icon center" mode="ios"
            name="checkmark-circle-outline"></ion-icon></ion-col>
      </ion-row>
      <ion-row style="text-align: center;">
        <!-- <ion-col size="12"><img class="center" src="../../../assets/{{logo.logoPath}}" ></ion-col> -->
        <ion-col size="12"><div class="font-H3">การโอนเงินสำเร็จ</div></ion-col>
        <ion-col size="12" style="color: #7E7C7C;"><div class="font-T2">{{time}}
            น.</div></ion-col>
        <ion-col size="12"><div><span class="font-H2" style="color:
              #1A864B;font-weight: bold;">2,000.00</span><span class="font-T3">
              บาท</span></div></ion-col>

      </ion-row>

      <!-- <h2 style="margin-left: 21px;font-weight: bold;color: #2B2B2B;margin-top: -14px;text-align: center;">ทำการโอนเงินเสร็จสิ้น</h2>
  <h2 style="margin-left: 21px;font-weight: bold;color: #2B2B2B;margin-top: -14px;text-align: center;">ท่านสามารถดูรายการเคลื่อนไหวได้</h2>
  <h2 style="margin-left: 21px;font-weight: bold;color: #2B2B2B;margin-top: -14px;text-align: center;">ที่เมนูเงินฝาก</h2> -->
      <!-- <div  class="wrapper">
    <div id="myBtn" style="
    font-size: 20px;
    font-weight: bold;
    line-height: 1;
    position: relative;
    border-radius: 28px;
    background: #27A323;
    background-clip: padding-box;
    color: #FFF;
    outline: none;
    width: 40%;
    padding: 5px;
    text-align: center;
    " class="fixsize" (click)="getSucessSCB()">ดูรายการเคลื่อนไหว</div>
  </div> -->
      <!-- <div  class="wrapper">
    <div id="myBtn" style="
    font-size: 20px;
        font-weight: bold;
        line-height: 1;
        position: relative;
        border-radius: 28px;
        background-clip: padding-box;
        color: #FFF;
        outline: none;
        width: 67%;
        padding: 10px;
        text-align: center;
    " class="fixsize color-primary-bg" (click)="getSucessSCB()" >ดูรายการเคลื่อนไหว</div>
    </div> -->
      <div class="ion-row">
        <ion-row style="margin-bottom: 7%;">
          <ion-col size="6"><div>รหัสอ้างอิง</div></ion-col>
          <ion-col size="6"><div class="right-side">45464045</div></ion-col>
        </ion-row>
        <ion-row style="margin-bottom: 7%;">
          <ion-col size="6"><div>จากบัญชีสหกรณ์</div></ion-col>
          <ion-col size="6"><div class="right-side">ศักดา ดีคำป้อ
            </div><br><div class="right-side">1-1254-1246-6</div></ion-col>
        </ion-row>
        <ion-row style="margin-bottom: 7%;">
          <ion-col size="6"><div>ไปยัง</div></ion-col>
          <ion-col size="6">
            <div class="right-side">ศักดา ดีคำป้อ</div><br>
            <div class="right-side">0-692-30122-8</div></ion-col>
        </ion-row>
        <!-- <ion-row style="
  margin-bottom: 7%;
">
    <ion-col size="6"><div>จำนวน</div></ion-col>
    <ion-col size="6"><div class="right-side">1,000 บาท</div></ion-col> 
</ion-row> -->
        <ion-row style="margin-bottom: 7%;">
          <ion-col size="6"><div>ค่าธรรมเนียม</div></ion-col>
          <ion-col size="6"><div class="right-side">0.00 บาท</div></ion-col>
        </ion-row>
        <!-- <ion-row style="
margin-bottom: 7%;
">
  <ion-col size="6"><div>วันที่/เวลา</div></ion-col>
  <ion-col size="6"><div class="right-side">{{time}} น.</div></ion-col> 
</ion-row> -->
        <!-- 
<div class="wrapper" style="margin-top: 3em;">
  <div id="myDIV" style="font-size: 20px;margin-top: 2%; font-weight: bold; line-height: 1; position: relative; border: 2px solid #27A323;
  border-radius: 28px; background: #27A323; background-clip: padding-box; color: #FFF; outline: none; width: 40%; padding: 5px; text-align: center;"
  class="fixsize" (click)="getSucessSCB()">ต่อไป</div>
  <div id="myDIV1" style="font-size: 20px;margin-top: 2%; font-weight: bold; line-height: 1; position: relative; border: 2px solid #27A323;
  border-radius: 28px; background: #27A323; background-clip: padding-box; color: #FFF; outline: none; width: 40%; padding: 5px; text-align: center;margin-left:3px"
  class="fixsize" (click)="shareImg()"><svg id="myDIV1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="15" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#ffffff"><path d="M6.86656,151.60188l-6.86656,-0.12094c-0.06719,-1.88125 -1.23625,-46.17125 27.99031,-76.4325c16.59531,-17.15969 39.56,-26.18969 68.32969,-26.84812v-35.83781l74.4975,56.4375l-74.4975,56.45094v-35.77062c-84.07844,1.58562 -89.39969,61.51687 -89.45344,62.12156z"></path></g></g></svg>แชร์</div>
  </div> 
<ion-row>
  <ion-col size="6"><div>บันทึกภาพเก็บไว้</div></ion-col>
  <ion-col size="6" (click)="shareImg()"><div class="right-side">แชร์</div></ion-col> 
</ion-row>
<hr style="
    border: 0.5px solid #707070;
    width: 98%;
    height: 0px !important;
    margin-top: 3em;
">   -->

      </div>
    </div>
    <div class="wrapper">
      <button class="fixsize" (click)="shareImg()">แชร์</button>
    </div>

