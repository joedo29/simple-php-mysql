
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        table, th, td {
        border-collapse: collapse;
        }
        th, td {
        padding: 5px;
        }
        table#t01 {
            width: 20%;
            background-color: powderblue;
        }

        th {
            text-align: left;
        }
    </style>
</head/
<body style="background-color:powderblue;">
<!--<img src="logo.png" style="width:128px;height:128px;">-->
<form action="/" method="post">
    <table border="0" id="t01">
        <center><h1> Bookshare </h1></center>
        <h3> SIGN UP </h3>
        <tr>
            <td>Username</td>
            <td align="center"><input type="text" name="username" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Email</td>
            <td align="center"><input type="text" name="email" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Password</td>
            <td align="center"><input type="text" name="password" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>City</td>
            <td align="center"><input type="text" name="city" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>State</td>
            <td align="center"><input type="text" name="state" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Phone</td>
            <td align="center"><input type="text" name="phone" size="30" placeholder="Enter your value here"/></td>
        </tr>


        <tr>
            <td>Zip Code</td>
            <td align="center"><input type="text" name="zip" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td colspan="2" align="center"><input type="submit" value="Sign Up"/></td>
        </tr>
    </table>
    <input type="hidden" name="action" value="signup"/>
</form>

</body>

</html>