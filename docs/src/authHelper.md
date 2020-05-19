# AuthHelper

#### Methods

###### getConnectionPDO()
###### consult($query, $debug)
###### consultLine($query, $debug)
###### buildInsertQuery($tableName, $data)
###### updateDatabase($tableName, $data)
###### insertIntoDatabase($tableName, $data)
###### getBindName($columnName)
###### debug($query)
<p> </p>

###### signUp($name, $email, $username, $password)
###### signIn($user, $password)
###### signOut()
###### confirmEmail($token)
###### sendConfirmEmail($user)
###### fogotPassword($email)
###### sendForgotPasswordEmail($user)
###### changePasswordWithToken($token, $newPassword)
###### setActionsExceptions($newActionsExceptions)
###### addActionExcept($newActionExcept)
###### checkLogin()
###### isLoggedIn()
###### existEmail($email)
###### existUsername($username)
###### generateToken($string)
###### checkLogin()
