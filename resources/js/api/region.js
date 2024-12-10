import request from '@/utils/request';

export function fetchRegions() {
  return request({
    url: '/regions',
    method: 'get',
  });
}

export function fetchRegion(regionId) {
  return request({
    url: `/regions/${regionId}`,
    method: 'get',
  });
}

export function createRegion(data) {
  return request({
    url: '/regions',
    method: 'post',
    data: data,
  });
}

export function updateRegion(regionId, data) {
  return request({
    url: `/regions/${regionId}`,
    method: 'put',
    data: data,
  });
}

export function deleteRegion(regionId) {
  return request({
    url: `/regions/${regionId}`,
    method: 'delete',
  });
}

export function fetchZones(regionId) {
  return request({
    url: `/regions/${regionId}/zones`,
    method: 'get',
  });
}
