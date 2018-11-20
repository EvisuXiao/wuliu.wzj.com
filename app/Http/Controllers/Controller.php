<?php

namespace App\Http\Controllers;

use App\Library\Utils;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var array|\Illuminate\Http\Request
     */
    protected $request = null;
    /**
     * 接口入参
     * @var array
     */
    protected $input = [];
    /**
     * $input根据rules过滤填充的入参
     * @var array
     */
    protected $payload = [];
    /**
     * 验证规则, 见配置validate
     * @var array
     */
    protected $rules = [
        'rules'     => [],
        'messages'  => [],
        'custom'    => []
    ];
    /**
     * 请求方式
     * @var string
     */
    protected static $method = '';

    public function __construct() {
        $this->request = request();
        self::$method = strtoupper($this->request->method());
        $this->_setInput();
        $this->_apiValidator();
        $this->_setPayload();
    }

    /**
     * 请求方式是否为GET
     * @return bool
     */
    protected static function isGet() {
        return self::$method == HTTP_METHOD_GET;
    }

    /**
     * 请求方式是否为POST
     * @return bool
     */
    protected static function isPost() {
        return self::$method == HTTP_METHOD_POST;
    }

    /**
     * 请求方式是否为PUT
     * @return bool
     */
    protected static function isPut() {
        return self::$method == HTTP_METHOD_PUT;
    }

    /**
     * 请求方式是否为DELETE
     * @return bool
     */
    protected static function isDelete() {
        return self::$method == HTTP_METHOD_DELETE;
    }

    public function label() {
        // 获取控制器名
        $controller = Utils::getControllerAndAction();
        $controller = $controller[0];
        // 获取默认列
        $fields = config("{$controller}.field_title");
        if(empty($fields)) {
            return $this->failReturn();
        }
        $show_fields = [];
        // 获取列属性
        $attr = config("{$controller}.field_attribute");
        // 添加列
        foreach($fields as $field => $title) {
            $each = [
                'prop'  => $field,
                'label' => $title,
                'width' => 100
            ];
            // 添加列属性
            foreach($attr as $key => $item) {
                isset($item[$field]) && $each[$key] = $item[$field];
            }
            $show_fields[] = $each;
        }
        return $this->succReturn($show_fields);
    }

    /**
     * api接口验证器
     * @throws ValidationException
     */
    private function _apiValidator() {
        $this->_resolveRules();
        if(!empty($this->rules['rules'])) {
            $validator = validator($this->input, $this->rules['rules'], $this->rules['messages'], $this->rules['custom']);
            if($validator->fails()) {
                $error = $validator->errors()->first();
                throw new ValidationException($validator, $this->failReturn($error));
            }
        }
    }

    /**
     * 解析api验证规则
     * @throws \Exception
     */
    private function _resolveRules() {
        // 根据controller与action获取api验证规则
        list($controller, $action) = Utils::getControllerAndAction();
        $replace = '__BASE__';
        foreach($this->rules as $key => &$item) {
            $rules = config(sprintf('validate.%s.%s.%s', $key, $controller, $action), []);
            foreach($rules as $field => &$rule) {
                if(is_array($rule)) {
                    // 不同请求方式支持不同验证规则
                    // BASE指代没有显式配置的规则
                    $base = $rule[VALIDATE_RULE_BASE] ?? '';
                    $rule = $rule[self::$method] ?? $base;
                    // 在某种请求方式未配置下无需验证
                    if(empty($rule)) {
                        unset($rules[$field]);
                        continue;
                    }
                    if(!is_string($rule)) {
                        throw new \Exception(sprintf('the type of api-validation-rules(field: %s) is not string', $field));
                    }
                    // __BASE__替换为公共规则
                    if(str_contains($rule, $replace)) {
                        if(!empty($base)) {
                            $rule = str_replace($replace, $base, $rule);
                        } else {
                            throw new \Exception(sprintf('can not find base rule in api-validation-rules (field: %s)', $field));
                        }
                    }
                }
            }
            $item = $rules;
        }
    }

    /**
     * 设置入参
     */
    private function _setInput() {
        foreach($this->request->all() as $key => $value) {
            $this->input[$key] = is_null($value) ? '' : $value;
        }
    }

    /**
     * 根据rules设置payload
     */
    private function _setPayload() {
        if(empty($this->rules['rules'])) {
            $this->payload = $this->input;
            return;
        }
        foreach($this->rules['rules'] as $key => $rule) {
            // 根据rule的键写入$payload
            // 如果不是required的话根据类型写入默认值
            if(isset($this->input[$key])) {
                $this->payload[$key] = $this->input[$key];
            } elseif(str_contains($rule, 'string')) {
                $this->payload[$key] = '';
            } elseif(str_contains($rule, 'numeric') || str_contains($rule, 'integer')) {
                $this->payload[$key] = 0;
            } elseif(str_contains($rule, 'array')) {
                $this->payload[$key] = [];
            } elseif(str_contains($rule, 'boolean')) {
                $this->payload[$key] = false;
            } else {
                $this->payload[$key] = null;
            }
        }
    }

    /**
     * api接口通用返回
     * @param int    $code
     * @param string $msg
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiReturn($code = HTTP_RESPONSE_SUCCESS_CODE, $msg = HTTP_RESPONSE_SUCCESS_MSG, $data = []) {
        $response = [
            'code'    => $code,
            'message' => $msg,
            'data'    => $data
        ];
        return response()->json($response);
    }

    /**
     * api接口成功返回
     * @param array  $data
     * @param string $msg
     * @param int    $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function succReturn($data = [], $msg = HTTP_RESPONSE_SUCCESS_MSG, $code = HTTP_RESPONSE_SUCCESS_CODE) {
        return $this->apiReturn($code, $msg, $data);
    }

    /**
     * api接口失败返回
     * @param string $msg
     * @param int    $code
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function failReturn($msg = HTTP_RESPONSE_FAIL_MSG, $code = HTTP_RESPONSE_FAIL_CODE, $data = []) {
        return $this->apiReturn($code, $msg, $data);
    }
}
