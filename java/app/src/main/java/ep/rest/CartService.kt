package ep.rest

import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object CartService {

    interface RestApi {

        companion object {
            const val URL = "https://10.0.2.2:8443/netbeans/Pivomat/php/api/"
        }

        @GET("kosarica")
        fun getAll(@Header("Cookie") cookie : String): Call<List<CartItem>>

        @GET("narocilo")
        fun posljiNarocilo(@Header("Cookie") cookie : String): Call<Void>

        @FormUrlEncoded
        @POST("kosarica")
        fun insert(@Field("id") idPiva: Int,
                   @Field("kol") kolicina: Int,
                   @Header("Cookie") cookie : String): Call<String>


        @DELETE("kosarica/{id}")
        fun delete(@Path("id") id: Int, @Header("Cookie") cookie : String): Call<Void>
    }

    val instance: RestApi by lazy {
        val retrofit = Retrofit.Builder()
                .baseUrl(RestApi.URL)
                .addConverterFactory(GsonConverterFactory.create())
                .client(myOkHttpClient.getUnsafeOkHttpClient())
                .build()

        retrofit.create(RestApi::class.java)
    }
}