package ep.rest

import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object PivoService {

    interface RestApi {

        companion object {
            const val URL = "http://10.0.2.2:8080/netbeans/Pivomat/php/api/"
        }

        @GET("piva")
        fun getAll(): Call<List<Pivo>>

        @GET("piva/{id}")
        fun get(@Path("id") id: Int, @Header("Cookie") cookie : String): Call<Pivo>

    }

    val instance: RestApi by lazy {
        val retrofit = Retrofit.Builder()
                .baseUrl(RestApi.URL)
                .addConverterFactory(GsonConverterFactory.create())
                .build()

        retrofit.create(RestApi::class.java)
    }
}