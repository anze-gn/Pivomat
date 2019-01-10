package ep.rest

import android.animation.Animator
import android.animation.AnimatorListenerAdapter
import android.annotation.TargetApi
import android.content.pm.PackageManager

import android.app.Activity
import android.os.AsyncTask
import android.os.Build
import android.os.Bundle
import android.provider.ContactsContract
import android.text.TextUtils
import android.view.View
import android.view.inputmethod.EditorInfo
import android.widget.ArrayAdapter
import android.widget.TextView



import android.Manifest.permission.READ_CONTACTS

import kotlinx.android.synthetic.main.activity_login.*
import android.content.Intent
import android.util.Log
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


/**
 * A login screen that offers login via email/password.
 */
class LoginActivity : Activity() {
    /**
     * Keep track of the login task to ensure we can cancel it if requested.
     */

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)
        // Set up the login form.

        email_sign_in_button.setOnClickListener {

            LoginService.instance.prijava(email.text.toString(), password.text.toString(), "").enqueue(object: Callback<String> {
                override fun onResponse(call: Call<String>?, response: Response<String>?) {
                    if (response!!.isSuccessful){
                        Log.i("PRIJAVA", response.body())
                        val app = application as PivomatApp
                        app.email = email.text.toString()
                        app.geslo = password.text.toString()
                        app.cookie = response.headers().get("Set-Cookie")
                        val intent = Intent(this@LoginActivity, MainActivityPrijavljen::class.java)
                        startActivity(intent)

                    } else {
                        Log.i("PRIJAVA", "lalal")
                        neuspesnaPrijava.text = "Napačen email ali geslo!"
                    }
                }

                override fun onFailure(call: Call<String>?, t: Throwable?) {
                    neuspesnaPrijava.text = "Napačen email ali geslo!"
                    Log.i("PRIJAVA", "hahaha")
                }


            })



        }
    }



}
