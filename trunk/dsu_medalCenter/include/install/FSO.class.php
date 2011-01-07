<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

/**
 * �ļ������࣬����CZW Framework(build 509)�����෢���� http://www.jhdxr.com/blog/html/tech/php-tech/fso-class-for-php.html
 *
 * @author ������Ϻ��
 */
if(class_exists('FSO')) {
    class FSO {
        /**
         * �����ļ����ļ���
         * @param <string> $source: �����Ƶ��ļ����ļ���
         * @param <string> $dest: Ŀ���ļ����ļ���
         * @param <int> $rewrite: ��Ŀ���ļ�����ʱ�Ƿ񸲸ǣ�Ĭ��ֵ1Ϊ���ǣ�
         */
        public static function copy($source, $dest, $rewrite = 1) {
            $rewrite = 0 == $rewrite ? 0 : 1;
            self::_move($source, $dest, $rewrite, 1);
        }

        /**
         * ɾ���ļ����ļ���
         * @param <string> $source: ��ɾ�����ļ����ļ���
         */
        public static function unlink($source) {
            self::_move($source, '', -1, 0);
            if(file_exists($source)){ //����ɾ��
            	self::_move($source, '', -1, 0);
            }
        }

        /**
         * �ƶ��ļ����ļ���
         * @param <string> $source: ���ƶ����ļ����ļ���
         * @param <string> $dest: Ŀ���ļ����ļ���
         * @param <int> $rewrite: ��Ŀ���ļ�����ʱ�Ƿ񸲸ǣ�Ĭ��ֵ1Ϊ���ǣ�
         */
        public static function move($source, $dest, $rewrite = 1) {
            $rewrite= 0 == $rewrite ? 0 : 1;
            self::_move($source, $dest, $rewrite, 2);
            if(self::_isEmpty($source)){ //ɾ��
            	self::unlink($source);
            }
        }
        
        /**
         * �������Ŀ¼��ͬʱ�趨�ļ���Ȩ�޺ʹ���index�ļ�
         * @param <string> $dir ��Ҫ�����Ķ��Ŀ¼
         * @param <int> $mode ģʽ����֧�ְ˽�����������0777
         * @param <bool> $makeindex �Ƿ񴴽�index.html�ļ�
         */
        public static function mkdir($dir, $mode = 0777, $makeindex = TRUE){
			if(!is_dir($dir)){
				self::mkdir(dirname($dir));
				@mkdir($dir, $mode);
				if($makeindex){
					@touch($dir.'/index.html');
					@chmod($dir.'/index.html', 0777);
				}
			}
			return true;
		}
        
        /**
         * �ı��ļ��м�����ߵ��ļ����ļ��е�ģʽ
         * @param <string> $source ��Ҫ������ģʽ���ļ���
         * @param <int> $mode ģʽ����֧�ְ˽�����������0777
         * @return <bool>���ȫ�����ĳɹ�����true�����򷵻�false
         */
        public static function dir_chmod($source, $mode) {
            $return=true;
            if(is_dir($source)) {
                $source.='/' == substr($source,-1)?'':'/';
                $dir=dir($source);
                while(false !== ($entry=$dir->read())) {
                    if($entry == '.' || $entry == '..') continue;
                    if(!self::chmod($source.$entry,$mode)) $return=false;
                }
                $dir->close();
            }else {
                if(!chmod($source, $mode)) return false;
            }
            return $return;
        }

        /**
         * ����ļ����ļ����Ƿ����777Ȩ�ޡ�
         * ��������winϵ�в���ϵͳ����Ч�����Ƿ���TRUE��
         * @param <string> $source Ҫ�����ļ����ļ���
         * @return <bool> �����Ƿ����777Ȩ��
         */
        public static function check777($source){
            return (';'==PATH_SEPARATOR || file_exists($source) && is_writeable($source));
        }

        /**
         * ���ơ��ƶ���ɾ���ļ����ļ���
         * @param <string> $source: ���ƶ����ļ����ļ���
         * @param <string> $dest: Ŀ���ļ����ļ���
         * @param <int> $rewrite: 1Ϊ�����Ҹ��ǣ�0�����ƣ�-1Ϊ������
         * @param <int> $reserved: �Ƿ���Դ�ļ���Ĭ��ֵ0Ϊ��������1Ϊ������2Ϊ��δ�ɹ�����ʱ������
         */
        private static function _move($source, $dest, $rewrite = 1, $reserved = 0) {
            if(is_dir($source)) {
                $source .= '/' == substr($source,-1) ? '' : '/';
                $dest .= $dest != '' && '/' == substr($dest,-1) ? '' : '/';
                $dir = dir($source);
                $rewrite >= 0 && @mkdir($dest);
                while(false !== ($entry = $dir->read())) {
                    if($entry == '.' || $entry == '..') continue;
                    self::_move($source.$entry, $dest.$entry, $rewrite, $reserved);
                }
                $dir->close();
                if(!$reserved) @rmdir($source);
            }else {
                //rename($source,$dest); url wrapper support??
                $destExist = (0 == $rewrite && 2 == $reserved) ? file_exists($dest) : false; //ֻ�е���Ҫʱ�Ž����ж�
                if(1 == $rewrite || (0 == $rewrite && !$destExist)) copy($source,$dest);
                if(0 == $reserved || (2 == $reserved && !$destExist)) unlink($source);
            }
        }
        
        private static function _isEmpty($source) {
        	$source .= '/' == substr($source,-1) ? '' : '/';
        	if(is_dir($source)) {
        		$dir = dir($source);
        		while(false !== ($entry = $dir->read())) {
        			if($entry == '.' || $entry == '..' || (is_dir($entry) && self::_isEmpty($source.$entry))) continue;
        			return false;
        		}
        		$dir->close();
        	}
        	return true;
        }
    }
}
?>
