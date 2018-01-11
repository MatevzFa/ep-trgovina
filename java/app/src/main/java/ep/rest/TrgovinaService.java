package ep.rest;

import java.util.List;

import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Path;

public class TrgovinaService {
    private static final String TAG = TrgovinaService.class.getCanonicalName();

    interface RestApi {
        String URL = "http://10.0.2.2/netbeans/ep-trgovina/php/index.php/api/";

        @GET("izdelki")
        Call<List<Izdelek>> getAll();

        @GET("izdelki/{id}")
        Call<Izdelek> get(@Path("id") int id);

        @FormUrlEncoded
        @POST("izdelki")
        Call<Void> insert(@Field("author") String author,
                          @Field("ime") String title,
                          @Field("cena") double price,
                          @Field("year") int year,
                          @Field("opis") String description);

        @FormUrlEncoded
        @PUT("izdelki/{id}")
        Call<Void> update(@Path("id") int id,
                          @Field("author") String author,
                          @Field("ime") String title,
                          @Field("cena") double price,
                          @Field("year") int year,
                          @Field("opis") String description);
    }

    private static RestApi instance;

    public static synchronized RestApi getInstance() {
        if (instance == null) {
            final Retrofit retrofit = new Retrofit.Builder()
                    .baseUrl(RestApi.URL)
                    .addConverterFactory(GsonConverterFactory.create())
                    .build();

            instance = retrofit.create(RestApi.class);
        }

        return instance;
    }
}
