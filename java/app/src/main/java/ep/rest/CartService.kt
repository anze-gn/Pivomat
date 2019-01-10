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

        @FormUrlEncoded
        @POST("kosarica")
        fun insert(@Field("idPiva") idPiva: Int,
                   @Field("kolicina") kolicina: Int,
                   @Field("cena") cena: Double,
                   @Field("naziv") naziv: String,
                   @Header("Cookie") cookie : String): Call<String>


        @DELETE("kosarica/{id}")
        fun delete(@Path("id") id: Int, @Header("Cookie") cookie : String): Call<List<CartItem>>
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