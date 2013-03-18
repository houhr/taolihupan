<?php
class editor{
	public $text='';
	public $showToolbar=15;
	public $uploadFilePath='';
	public $lightBox='';
	public function __set($name,$value){
		$this -> name = $value;
	}
	public function display(){
?>
	<div class="alEditor">
<?php
		if($this -> showToolbar != 0){
			echo '<div class="toolbar">'."\n";
			/*if(($this -> showToolbar&8) != 0){
				echo '<button type="button" onclick="addTitle()" title="设为标题">标题</button>'."\n";
			}*/
			if(($this -> showToolbar&4) != 0){
				echo '<button type="button" onclick="rightUrlForImage=false; openNewWindow(\'image\')" title="插入图片">图片</button>'."\n";
			}
			if(($this -> showToolbar&2) != 0){
				echo '<button type="button" onclick="openNewWindow(\'link\');" title="插入链接">链接</button>'."\n";	
			}
			if(($this -> showToolbar&1) != 0){
				echo '<button type="button" onclick="openNewWindow(\'add_maps\');selectGmap();" title="插入地图">地图</button>'."\n";
			}
			echo '</div>'."\n";
		}
?>
		<div class="smily">
			<a class="floatLeft" onmouseover="setTimer(false)" onmouseout="clearTimer()">&nbsp;&nbsp;&lt;&nbsp;&nbsp;</a>
			<div class="marquee" id="smilyBar">
			<table border="0" id="smilys"><tr>
				<td nowrap="true"><a onclick="addText(' ~~~^_^~~~ ')" title="笑死我了">~~~^_^~~~</a></td>
				<td nowrap="true"><a onclick="addText(' =_= ')" title="困">=_=</a></td>
				<td nowrap="true"><a onclick="addText(' $_$ ')" title="好多钱啊">$_$</a></td>
				<td nowrap="true"><a onclick="addText(' +_+ ')" title="晕">+_+</a></td>
				<td nowrap="true"><a onclick="addText(' o(>﹏<)o ')" title="不要啊">o(>﹏<)o</a></td>
				<td nowrap="true"><a onclick="addText(' O(∩_∩)O哈哈~ ')" title="哈哈">O(∩_∩)O~</a></td>
				<td nowrap="true"><a onclick="addText(' (*^◎^*) ')" title="好啊">(*^◎^*)</a></td>
				<td nowrap="true"><a onclick="addText(' o(≧v≦)o~~好棒 ')" title="好棒">o(≧v≦)o~~</a></td>
				<td nowrap="true"><a onclick="addText(' {{{(>_<)}}} ')" title="好冷">{{{(>_<)}}}</a></td>
				<td nowrap="true"><a onclick="addText(' (～ o ～)~zZ ')" title="呼呼">(～o～)~zZ</a></td>
				<td nowrap="true"><a onclick="addText(' o(╯□╰)o ')" title="囧">o(╯□╰)o</a></td>
				<td nowrap="true"><a onclick="addText(' ~(@^_^@)~ ')" title="羞">~(@^_^@)~</a></td>
				<td nowrap="true"><a onclick="addText('（ˉ﹃ˉ）')" title="口水">（ˉ﹃ˉ）</a></td>
				<td nowrap="true"><a onclick="addText(' ~~o(>_<)o~~ ')" title="泪奔">~~o(>_<)o~~</a></td>
				<td nowrap="true"><a onclick="addText(' (╰_╯)# ') " title="找死">(╰_╯)#</a></td>
				<td nowrap="true"><a onclick="addText(' \\(^o^)/ ') " title="yeah!">\(^o^)/</a></td>
				<td nowrap="true"><a onclick="addText(' o(︶︿︶)o唉 ')" title="心情不好">o(︶︿︶)o</a></td>
				<td nowrap="true"><a onclick="addText(' ~\(≧▽≦)/~ ')" title="万岁">~\(≧▽≦)/~</a></td>
				<td nowrap="true"><a onclick="addText(' ╮(╯_╰)╭ ')" title="无所谓">╮(╯_╰)╭</a></td>
				<td nowrap="true"><a onclick="addText(' \(^o^)/YES！ ')" title="对">\(^o^)/YES!</a></td>
				<td nowrap="true"><a onclick="addText(' Y(^o^)Y ')" title="好了">Y(^o^)Y</a></td>
			</tr></table>
			</div>
			<a class="floatRight" onmouseover="setTimer(true)" onmouseout="clearTimer()">&nbsp;&nbsp;&gt;&nbsp;&nbsp;</a>
		</div>
		<textarea id="editorArea" name="editorArea"> <?php if(!empty($_POST['editorArea'])) echo $_POST['editorArea']; ?> </textarea>
		</div>
		<div id="fade" class="black_overlay">
		<div id="link" class="link_content">
			<p>链接地址：
				<input type="text" id="linkAddress" name="linkAddress" size="40" 
					onkeydown="if(event.keyCode==13){stopBubble(event);linkSubmit();}"/>
				<button type="button" class="floatRight" onclick="closeWindow('link',true)">取消</button>
				<button type="button" class="floatRight" onclick="linkSubmit()">确定</button>
			</p>
		</div>
		<div id="image" class="image_content">
			<table>
			<tr>
			<td valign="top">
			插入图片 URL :
			</td>
			<td valign="top">
				<input type="text" id="imageAddress" name="imageAddress" size="40" 
					onkeydown="if(event.keyCode==13){stopBubble(event);imageSubmit();}" />
			</td>
			<td>
				<button type="button" onclick="imageSubmit()">确定</button><br/>
			</td>
			</tr>
			<tr>
			<td>
			插入上传图片：
			</td>
			<td>
			<iframe scrolling="no" height="25px"  frameborder="0" src="<?php echo toAbsAdd($_SERVER['HTTP_HOST'].'/taolihupan'.$_SERVER['PHP_SELF'],
				'/includes/upload_from.php?path='.$this->uploadFilePath);?>"></iframe>
			</td>
			<td>
				<button type="button" onclick="closeWindow('image',true)">取消</button>
			</td>
			</tr>
			<tr>
			<td colspan="3" align="center">
			<div class="preView"> 
				<img id="view_image" alt="图片预览" width="100%" onerror="rightUrlForImage=false;"/>
			</div>
			<td>
			</table>
		</div>
		<div id="add_maps" class="map_content">
			<div class="map_view" >
				<div class="map_tag">
					<dt>
						<a id="gmap_bnt" name="gmap_bnt" onclick="selectGmap()">Google 地图</a>
						<a id="ndmapm_bnt" name="ndmapm_bnt" onclick="selectNdmapm()">校本部</a>
					</dt>
				</div>
				<div class="map_self" id="map_self_panel">
					<div id="gmap" name="gmap" style="width:100%;height:100%;">	</div>
					<div id="ndmapm_dg" name="ndmapm_dg" class="ndmapm" ondragstart="return false">
						<div id="ndmapm_nav" name="ndmapm_nav" class="navm">
							<img id="map_mask" name="map_mask" 
								src="<?php echo toAbsAdd($_SERVER['HTTP_HOST'].'/taolihupan'.$_SERVER['PHP_SELF'],
								'/images/masking.png');?>" onclick='placeNdmapMarker(event)'>
							</img>
							<input type="hidden" id="markerHome" name="markerHome" 
								value="<img src='<?php echo toAbsAdd($_SERVER['HTTP_HOST'].'/taolihupan'.$_SERVER['PHP_SELF']
								,'/images/marker.png');?>' title='%title%' style='top:%y%px;left:%x%px;position:absolute;'/>">
							</input>
						</div>
					</div>
				</div>
			</div>
			<div class="map_controller">
				<h3>输入描述信息</h3>
				↓<br/>
				<input type="text" id="map_msg" name="map_msg" class="msg"></input>
				↓<br/>
				<button type="button" class="tagButton" onclick="addTag()">添加　　</button><br/>
				↓<br/>
				<h3>←点选地点</h3><br/>
				<button type="button" onclick="addMap()">确定</button>  
				<button type="button" onclick="closeWindow('add_maps',true)">取消</button>
			</div>
		</div>
		<?php
			echo $this -> lightBox;
		?>
		</div>
<?php
		}
	}
?>
