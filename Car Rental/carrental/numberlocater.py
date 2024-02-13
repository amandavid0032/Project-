import phonenumbers
import opencage
import folium
from phonenumbers import timezone,geocoder,carrier
number = input("Enter the number with +__:")
phone = phonenumbers.parse(number)
time= timezone.time_zones_for_number(phone)
reg = geocoder.description_for_number(phone,"en")
print(phone)
print(time)
print(carrier)
print(reg)

from opencage.geocoder import OpenCageGeocode

key = 'e57a72c6b2c24658b044a53b59d1efcb'
geocoder = OpenCageGeocode(key)
query = str(reg)
results = geocoder.geocode(query)
# print(results)
lat = results[0]['geometry']['lat']
lng = results[0]['geometry']['lng']
print(lat,lng)

mymap = folium.Map(location = [lat,lng], zoom_start =9 )
folium.Marker([lat,lng], popup=reg).add_to(mymap)

mymap.save("Mylocation.html")
