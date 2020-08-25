<?php

namespace app\commands;

use app\rbac\Rbac;
use app\modules\user\models\User;
use yii\base\Model;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;


class SetupController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @internal param string $message the message to be echoed.
     */
    public function actionInit()
    {
        echo "Create Admin User:" . PHP_EOL;
        $model = new User();
        $this->readValue($model, 'username');
        $this->readValue($model, 'email');
        $model->setPassword($this->prompt('Password:', [
            'required' => true,
            'pattern' => '#^.{6,255}$#i',
            'error' => 'More than 6 symbols',
        ]));
        //$model->generateAuthKey();
        $model->status = User::STATUS_ADMIN;
        $this->log($model->save());
    }

    /**
     * @param Model $model
     * @param string $attribute
     */
    private function readValue($model, $attribute)
    {
        $model->$attribute = $this->prompt(mb_convert_case($attribute, MB_CASE_TITLE, 'utf-8') . ':', [
            'validator' => function ($input, &$error) use ($model, $attribute) {
                $model->$attribute = $input;
                if ($model->validate([$attribute])) {
                    return true;
                } else {
                    $error = implode(',', $model->getErrors($attribute));
                    return false;
                }
            },
        ]);
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }

    public function actionMigcity()
    {
        echo 'Start geo migration' . PHP_EOL;
        $geo = [];
        for ($i = 1; $i <= 23; $i++) {
            $file = Json::decode(file_get_contents(__DIR__ . '/../migrations/dump/belarus-cities-' . $i . '.json'));
            foreach ($file['response'] as $item) {
                $geo[md5($item['region'])]['region'] = $item['region'];
                $geo[md5($item['region'])][md5($item['area'])]['area'] = $item['area'];
                $geo[md5($item['region'])][md5($item['area'])]['cities'][] = $item['title'];
            }
        }

        $this->saveToFile($geo, __DIR__ . '/../migrations/dump/geo.php');
        echo "END" . PHP_EOL;
    }

    /**
     * Saves the authorization data to a PHP script file.
     *
     * @param array $data the authorization data
     * @param string $file the file path.
     * @see loadFromFile()
     */
    public function saveToFile($data, $file)
    {
        file_put_contents($file, "<?php\nreturn " . VarDumper::export($data) . ";\n", LOCK_EX);
        self::invalidateScriptCache($file);
    }

    /**
     * Invalidates precompiled script cache (such as OPCache or APC) for the given file.
     * @param string $file the file path.
     * @since 2.0.9
     */
    protected static function invalidateScriptCache($file)
    {
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
        }
        if (function_exists('apc_delete_file')) {
            @apc_delete_file($file);
        }
    }
}
