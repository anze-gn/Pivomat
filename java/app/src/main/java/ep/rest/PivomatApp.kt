package ep.rest

import android.app.Application
import android.content.Context
import com.franmontiel.persistentcookiejar.PersistentCookieJar
import com.franmontiel.persistentcookiejar.cache.SetCookieCache
import com.franmontiel.persistentcookiejar.persistence.SharedPrefsCookiePersistor
import okhttp3.Cookie
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory


class PivomatApp : Application() {

    var cookie : Cookie? = null

//    override fun onCreate() {
//        super.onCreate()
//        val context : Context = this!!.applicationContext
//    }

    val instance: PivoService.RestApi by lazy {
        val context : Context = this.applicationContext
        val cookieJar = PersistentCookieJar(SetCookieCache(), SharedPrefsCookiePersistor(context))
        val retrofit = Retrofit.Builder()
                .baseUrl(PivoService.RestApi.URL)
                .addConverterFactory(GsonConverterFactory.create())
                .client(OkHttpClient.Builder()
                        .cookieJar(cookieJar)
                        .build())
                .build()

        retrofit.create(PivoService.RestApi::class.java)
    }


}