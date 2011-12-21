<?php

class LayoutModule {
	public $id, $uuid, $name, $version;
	public $attrs;

	public function __construct(array $attrs) {
		$this->attrs = array();

		foreach ($attrs as $k => $v) {
			$p = strpos($k, ':');
			$k = $p ? substr($k, $p + 1) : $k;

			if (in_array($k, array('id', 'name', 'uuid', 'version'))) {
				$this->{$k} = $v;
			}
			else {
				$this->attrs[$k] = $v;
			}
		}
	}
}

class Layout extends AbstractModel {
	public $defaults;

	public function __construct($id) {
		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT id, uuid, template, created_ts, modified_ts, status FROM layouts WHERE id = :id');
		$stmt->bindParam(':id', $id);

		if ($stmt->execute() && $this->_model_data = $stmt->fetch(PDO::FETCH_OBJ));
		else {
			throw new NotFoundException('Layout not found: '. var_export($id, true));
		}

		$this->defaults = new LayoutDefaults($id);
	}

	public function render($uri, array $request, array $data) {
		$out = '';

		foreach ($this->explode() as $module_chunk) {
			if ($module_chunk instanceof LayoutModule) {
				$module = new JSModule('4eb436c8-38fc-426f-a53f-2b5c0acc4267' /*$module_chunk->uuid*/);
				$out .= $module->render('view', array(), $request, $this->defaults->union($module_chunk->id, $data));
			}
			else {
				$out .= $module_chunk;
			}
		}

		return $out;
	}

	private function explode() {
		return $this->explodeXML();
	}

	private function explodeXML() {
		$split = preg_split('#(<module.*>.*</module>)#imsU', $this->_model_data->template, 0, PREG_SPLIT_DELIM_CAPTURE);
		$i = 0;

		foreach ($split as &$piece) {
			if ($i % 2 != 0) {
				$dom = new DomDocument('1.0', 'UTF-8');
				$dom->loadXML('<root xmlns:m="http://platform.syncapse.com/xmlns/module">'. $piece .'</root>');

				$piece = array();
				foreach ($dom->documentElement->childNodes->item(0)->attributes as $attr) {
					$piece[$attr->name] = $attr->value;
				}

				$piece = new LayoutModule($piece);
			}

			$i++;
		}

		return $split;
	}

	private function explodeRegex() {
		$split = preg_split('#(<module.*>.*</module>)#imsU', $this->_model_data->template, 0, PREG_SPLIT_DELIM_CAPTURE);

		$i = 0;

		foreach ($split as &$piece) {
			if ($i % 2 != 0) {

				$attribs = array();

				if (preg_match('#<module((\s+([a-z:]+)=("[^"]+"|\'[^\']+\'))+)>#i', $piece, $matches)) {
					if (preg_match_all('#([a-z:]+)=("([^"]+)"|\'([^\']+)\')#', $matches[1], $attr)) {
						foreach ($attr[1] as $k => $attr_name) {
							$attribs[$attr_name] = $attr[3][$k] ? $attr[3][$k] : $attr[4][$k];
						}
					}
					else {
						throw new Exception('Unable to parse attributes: '. $matches[1]);
					}
				}
				else {
					throw new Exception('Unable to replace callback module: '. $piece);
				}

				$piece = new LayoutModule($attribs);
			}

			$i++;
		}

		return $split;
	}


}

?>
