<?php

	class  Vcode {
		private $width;                               //��֤��ͼƬ�Ŀ��
		private $height;                              //��֤��ͼƬ�ĸ߶�
		private $codeNum;                             //��֤���ַ��ĸ���
		private $disturbColorNum;                     //����Ԫ������
		private $checkCode;                           //��֤���ַ�
		private $image;                               //��֤����Դ


		function __construct($width=80, $height=20, $codeNum=4) {
			$this->width=$width;                     //Ϊ��Ա����width��ʹ��
			$this->height=$height;                     //Ϊ��Ա����height��ʹ��
			$this->codeNum=$codeNum;               //Ϊ��Ա����codeNum��ʹ��
			$number=floor($height*$width/15);
			if($number > 240-$codeNum)
				$this->disturbColorNum=240-$codeNum;
			else
				$this->disturbColorNum=$number;
			$this->checkCode=$this->createCheckCode();  //Ϊ��Ա����checkCode��ʹ��
		}
		/**
		 * ���������֤��ͼƬ��Ҳ���������SESSION�б�������֤��
		 * ʹ��echo ������󼴿�
		 * @return string	��֤��
		 */
		
		function __toString(){
			$_SESSION["code"]=strtoupper($this->checkCode);  //�ӵ�session��
			$this->outImg();              //�����֤��
			return '';
		}

		private function outImg(){                       //ͨ�����ʸ÷���������������ͼ��
			$this->getCreateImage();                 //�����ڲ���������������������г�ʹ��
			$this->setDisturbColor();                 //��ͼ��������һЩ��������
			$this->outputText();                     //��ͼ�������������ַ���
			$this->outputImage();                    //������Ӧ��ʽ��ͼ�����
		}


		private function getCreateImage(){              //��������ͼ����Դ������ʹ����Ӱ
			$this->image=imagecreatetruecolor($this->width,$this->height);
      			
			$backColor = imagecolorallocate($this->image, rand(225,255),rand(225,255),rand(225,255));    //����ɫ�������
			 @imagefill($this->image, 0, 0, $backColor);
		
			$border=imageColorAllocate($this->image, 0, 0, 0);
			imageRectangle($this->image,0,0,$this->width-1,$this->height-1,$border);
		}
		private function createCheckCode(){           
			//��������û�ָ���������ַ���,ȥ�������׻������ַ�oOLlz������012
			$code="3456789abcdefghijkmnpqrstuvwxyABCDEFGHIJKMNPQRSTUVWXY";
			for($i=0;$i<$this->codeNum;$i++) {
				$char=$code{rand(0,strlen($code)-1)};
				
				$ascii.=$char;
			}	
			return $ascii;	

		}	
		private function setDisturbColor() {    
			//���ø������أ���ͼ���������ͬ��ɫ��100����
			for($i=0; $i<=$this->disturbColorNum; $i++) {
				$color = imagecolorallocate($this->image, rand(0,255), rand(0,255), rand(0,255));
   				imagesetpixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2),$color);
			}

			for($i=0; $i<10; $i++){
				$color=imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
				imagearc($this->image,rand(-10,$this->width),rand(-10,$this->height),rand(30,300),rand(20,200),55,44,$color);
			}  
		}


		private function outputText() {       
			//�����ɫ������ڷš�����ַ�����ͼ�������
			for ($i=0;$i<=$this->codeNum;$i++) {
				$fontcolor = imagecolorallocate($this->image, rand(0,128), rand(0,128), rand(0,128));
				$fontSize=rand(6,5);
				$x = floor($this->width/$this->codeNum)*$i+3;
   				$y = rand(0,$this->height-imagefontheight($fontSize));
				imagechar($this->image, $fontSize, $x, $y, $this->checkCode{$i}, $fontcolor); 
 			  }
		}

		private function outputImage(){              
			//�Զ����GD֧�ֵ�ͼ�����ͣ������ͼ��
			if(imagetypes() & IMG_GIF){          //�ж�����GIF��ʽͼ��ĺ����Ƿ����
				header("Content-type: image/gif");  //���ͱ�ͷ��Ϣ����MIME����Ϊimage/gif
				imagegif($this->image);           //��GIF��ʽ��ͼ������������
			}elseif(imagetypes() & IMG_JPG){      //�ж�����JPG��ʽͼ��ĺ����Ƿ����
				header("Content-type: image/jpeg"); //���ͱ�ͷ��Ϣ����MIME����Ϊimage/jpeg
				imagejpeg($this->image, "", 0.5);   //��JPEN��ʽ��ͼ������������
			}elseif(imagetypes() & IMG_PNG){     //�ж�����PNG��ʽͼ��ĺ����Ƿ����
				header("Content-type: image/png");  //���ͱ�ͷ��Ϣ����MIME����Ϊimage/png
				imagepng($this->image);          //��PNG��ʽ��ͼ������������
			}elseif(imagetypes() & IMG_WBMP){   //�ж�����WBMP��ʽͼ��ĺ����Ƿ����
				 header("Content-type: image/vnd.wap.wbmp");   //���ͱ�ͷΪimage/wbmp
				 imagewbmp($this->image);       //��WBMP��ʽ��ͼ������������
			}else{                              //���û��֧�ֵ�ͼ������
				die("PHP��֧��ͼ�񴴽���");    //�����ͼ�����һ������Ϣ�����˳�����
			}	
		}
		function __destruct(){                      //���������֮ǰ����ͼ����Դ�ͷ��ڴ�
 			imagedestroy($this->image);            //����GD���еķ�������ͼ����Դ
		}
	}
